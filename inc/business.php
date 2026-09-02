<?php
/**
 * Business details.
 *
 * Address, phone, hours and price band, entered once in wp-admin and reused by
 * the schema graph, the footer and /llms.txt.
 *
 * Every field ships empty on purpose. Schema properties are emitted only when
 * they hold a real value: a fabricated address or telephone number in
 * structured data is a false claim about a real business, and in local search
 * it is actively harmful - Google cross-references this against Google
 * Business Profile and directory citations, and inconsistent NAP data damages
 * ranking more than absent NAP data does.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const MAGENTA_BUSINESS_OPTION = 'magenta_business';

/**
 * Field definitions. Drives the settings screen, the sanitiser and the schema.
 *
 * @return array<string, array{label:string, type:string, hint:string}>
 */
function magenta_business_fields(): array {
	return array(
		'street'      => array(
			'label' => __( 'Street address', 'magenta' ),
			'type'  => 'text',
			'hint'  => __( 'e.g. 123 North Sound Road, Unit 4', 'magenta' ),
		),
		'locality'    => array(
			'label' => __( 'Town / district', 'magenta' ),
			'type'  => 'text',
			'hint'  => __( 'e.g. George Town', 'magenta' ),
		),
		'region'      => array(
			'label' => __( 'Island', 'magenta' ),
			'type'  => 'text',
			'hint'  => __( 'e.g. Grand Cayman', 'magenta' ),
		),
		'postal'      => array(
			'label' => __( 'Postal code', 'magenta' ),
			'type'  => 'text',
			'hint'  => __( 'e.g. KY1-1001', 'magenta' ),
		),
		'country'     => array(
			'label' => __( 'Country code', 'magenta' ),
			'type'  => 'text',
			'hint'  => __( 'Two letters. KY for the Cayman Islands.', 'magenta' ),
		),
		'telephone'   => array(
			'label' => __( 'Telephone', 'magenta' ),
			'type'  => 'text',
			'hint'  => __( 'Full international format, e.g. +1-345-555-0100', 'magenta' ),
		),
		'email'       => array(
			'label' => __( 'Public email', 'magenta' ),
			'type'  => 'email',
			'hint'  => __( 'The address you are happy to publish.', 'magenta' ),
		),
		'latitude'    => array(
			'label' => __( 'Latitude', 'magenta' ),
			'type'  => 'text',
			'hint'  => __( 'From Google Maps: right-click the pin, copy the first number.', 'magenta' ),
		),
		'longitude'   => array(
			'label' => __( 'Longitude', 'magenta' ),
			'type'  => 'text',
			'hint'  => __( 'The second number from the same place.', 'magenta' ),
		),
		'price_range' => array(
			'label' => __( 'Price range', 'magenta' ),
			'type'  => 'text',
			'hint'  => __( 'A band, not a price list. Google expects $ to $$$$.', 'magenta' ),
		),
		'socials'     => array(
			'label' => __( 'Social profiles', 'magenta' ),
			'type'  => 'textarea',
			'hint'  => __( 'One full URL per line - Facebook, LinkedIn, Google Business Profile, TikTok, Pinterest, a Yelp or directory listing. These become the sameAs links that tell search engines and AI assistants the profile they already know and this website are the same business.', 'magenta' ),
		),
		'hours'       => array(
			'label' => __( 'Opening hours', 'magenta' ),
			'type'  => 'textarea',
			'hint'  => __( 'One line per block: Mo-Fr 08:00-17:00. Use Sa and Su for the weekend. Leave blank if the studio is by appointment.', 'magenta' ),
		),
	);
}

/**
 * Values known from the client directly, used when wp-admin has nothing saved.
 *
 * This is not a place to guess. A default belongs here only once the studio
 * has actually confirmed the value, and the admin screen still overrides it -
 * the point is that a confirmed detail works the moment the theme syncs,
 * without depending on someone remembering to retype it into a form.
 *
 * @return array<string, string>
 */
function magenta_business_defaults(): array {
	return array(
		// All confirmed by the studio directly. Nothing here is inferred: the
		// address in particular is what promotes the entity from a plain
		// Organization to a LocalBusiness in the schema graph, so a guess
		// would be a false claim Google cross-references against the Google
		// Business Profile.
		'email'     => 'hello@magenta.ky',
		'telephone' => '+1 345 938 4902',
		'street'    => 'Unit 2, Monarch Suites, Caterpillar Ln',
		'locality'  => 'Grand Cayman',
		'postal'    => 'KY1-1204',
		'country'   => 'KY',
		// From the place's own Google Maps URL, so the pin and the schema
		// geo node agree with each other.
		'latitude'  => '19.3001733',
		'longitude' => '-81.3661463',
	);
}

function magenta_business(): array {
	$saved = get_option( MAGENTA_BUSINESS_OPTION, array() );
	$saved = is_array( $saved ) ? $saved : array();

	// A saved value wins, but only if it actually holds something - a field
	// blanked in wp-admin should not out-rank a detail we know is correct.
	$out = magenta_business_defaults();
	foreach ( $saved as $key => $value ) {
		if ( '' !== trim( (string) $value ) ) {
			$out[ $key ] = $value;
		}
	}

	return $out;
}

/**
 * A single field, or '' when unset.
 */
function magenta_business_field( string $key ): string {
	$all = magenta_business();
	return isset( $all[ $key ] ) ? (string) $all[ $key ] : '';
}

/**
 * Parse the hours textarea into schema OpeningHoursSpecification entries.
 *
 * Accepts "Mo-Fr 08:00-17:00" per line. Anything that does not parse is
 * skipped rather than guessed at.
 *
 * @return array<int, array<string, mixed>>
 */
function magenta_business_hours_schema(): array {
	$raw = trim( magenta_business_field( 'hours' ) );
	if ( '' === $raw ) {
		return array();
	}

	$days = array(
		'mo' => 'Monday',
		'tu' => 'Tuesday',
		'we' => 'Wednesday',
		'th' => 'Thursday',
		'fr' => 'Friday',
		'sa' => 'Saturday',
		'su' => 'Sunday',
	);
	$order = array_keys( $days );

	$out = array();

	foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}

		if ( ! preg_match( '/^([A-Za-z]{2})(?:\s*-\s*([A-Za-z]{2}))?\s+(\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})$/', $line, $m ) ) {
			continue;
		}

		$from = strtolower( $m[1] );
		$to   = $m[2] ? strtolower( $m[2] ) : $from;

		if ( ! isset( $days[ $from ], $days[ $to ] ) ) {
			continue;
		}

		$start = array_search( $from, $order, true );
		$end   = array_search( $to, $order, true );
		$span  = array();

		for ( $i = $start; ; $i = ( $i + 1 ) % 7 ) {
			$span[] = $days[ $order[ $i ] ];
			if ( $i === $end ) {
				break;
			}
			if ( count( $span ) > 7 ) {
				break;
			}
		}

		$out[] = array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => $span,
			'opens'     => $m[3],
			'closes'    => $m[4],
		);
	}

	return $out;
}

/**
 * The sameAs list: every profile that corroborates this is the same business.
 *
 * sameAs is how an entity gets confirmed rather than merely asserted. A site
 * claiming to be a business is one source; that claim agreeing with an
 * Instagram profile, a Google Business Profile and a directory listing is what
 * turns it into a resolved entity that can be cited with confidence.
 *
 * The Instagram account is known and hardcoded. Anything else is whatever has
 * been entered in Appearance > Magenta Business. Entries that are not valid
 * absolute URLs are dropped rather than published broken.
 *
 * @return array<int, string>
 */
function magenta_business_same_as(): array {
	$urls = array( 'https://www.instagram.com/magentacayman/' );

	foreach ( preg_split( '/\R/', magenta_business_field( 'socials' ) ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}

		$url = esc_url_raw( $line );
		if ( '' === $url || ! wp_http_validate_url( $url ) ) {
			continue;
		}

		$urls[] = $url;
	}

	return array_values( array_unique( $urls ) );
}

/**
 * A map query string, or '' when there is no address to point at.
 *
 * Deliberately returns nothing until the street and locality are both filled
 * in. A map centred on "Grand Cayman" is not a location, and dropping a pin on
 * a guessed building is worse than showing no map at all.
 */
function magenta_business_map_query(): string {
	$street   = magenta_business_field( 'street' );
	$locality = magenta_business_field( 'locality' );

	if ( '' === $street || '' === $locality ) {
		return '';
	}

	$parts = array( $street, $locality, magenta_business_field( 'postal' ), 'Cayman Islands' );

	return implode( ', ', array_filter( array_map( 'trim', $parts ) ) );
}

/**
 * PostalAddress node, or null when nothing usable is set.
 *
 * Returns null unless at least the locality and country are present -
 * a half-filled address is worse than none.
 */
function magenta_business_address_schema(): ?array {
	$locality = magenta_business_field( 'locality' );
	$country  = magenta_business_field( 'country' );

	if ( '' === $locality || '' === $country ) {
		return null;
	}

	$address = array(
		'@type'           => 'PostalAddress',
		'addressLocality' => $locality,
		'addressCountry'  => strtoupper( $country ),
	);

	foreach ( array(
		'streetAddress'   => 'street',
		'addressRegion'   => 'region',
		'postalCode'      => 'postal',
	) as $prop => $key ) {
		$value = magenta_business_field( $key );
		if ( '' !== $value ) {
			$address[ $prop ] = $value;
		}
	}

	return $address;
}

function magenta_business_geo_schema(): ?array {
	$lat = magenta_business_field( 'latitude' );
	$lng = magenta_business_field( 'longitude' );

	if ( ! is_numeric( $lat ) || ! is_numeric( $lng ) ) {
		return null;
	}

	return array(
		'@type'     => 'GeoCoordinates',
		'latitude'  => (float) $lat,
		'longitude' => (float) $lng,
	);
}

/**
 * How many fields are still blank - surfaced as a badge so it does not get
 * quietly forgotten.
 */
function magenta_business_missing(): int {
	$missing = 0;
	foreach ( array_keys( magenta_business_fields() ) as $key ) {
		if ( '' === magenta_business_field( $key ) ) {
			++$missing;
		}
	}
	return $missing;
}

/* -------------------------------------------------------------------------
 * Admin screen
 * ---------------------------------------------------------------------- */

function magenta_business_menu(): void {
	$missing = magenta_business_missing();
	$title   = __( 'Magenta Business', 'magenta' );
	if ( $missing ) {
		$title .= sprintf( ' <span class="awaiting-mod"><span class="pending-count">%d</span></span>', $missing );
	}

	add_theme_page(
		__( 'Magenta Business', 'magenta' ),
		$title,
		'edit_theme_options',
		'magenta-business',
		'magenta_business_screen'
	);
}
add_action( 'admin_menu', 'magenta_business_menu' );

function magenta_business_register(): void {
	register_setting(
		'magenta_business_group',
		MAGENTA_BUSINESS_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'magenta_business_sanitize',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'magenta_business_register' );

function magenta_business_sanitize( $input ): array {
	$clean  = array();
	$fields = magenta_business_fields();

	if ( ! is_array( $input ) ) {
		return $clean;
	}

	foreach ( $input as $key => $value ) {
		if ( ! isset( $fields[ $key ] ) ) {
			continue;
		}

		if ( 'email' === $fields[ $key ]['type'] ) {
			$clean[ $key ] = sanitize_email( $value );
		} elseif ( 'textarea' === $fields[ $key ]['type'] ) {
			$clean[ $key ] = sanitize_textarea_field( $value );
		} else {
			$clean[ $key ] = sanitize_text_field( $value );
		}
	}

	return $clean;
}

function magenta_business_screen(): void {
	$fields = magenta_business_fields();
	$values = magenta_business();
	?>
	<div class="wrap magenta-media">
		<h1><?php esc_html_e( 'Magenta Business', 'magenta' ); ?></h1>

		<div class="magenta-media__intro">
			<p>
				<?php esc_html_e( 'These details feed the structured data search engines and AI assistants read to understand the business - the hidden business card behind the site. Nothing here is invented: each property is published only once you fill it in.', 'magenta' ); ?>
			</p>
			<p class="description">
				<?php esc_html_e( 'Enter the address, phone and hours exactly as they appear on Google Business Profile and any directory listings. Inconsistent details across sources damage local ranking more than missing details do, so matching matters more than completeness.', 'magenta' ); ?>
			</p>
		</div>

		<form method="post" action="options.php">
			<?php settings_fields( 'magenta_business_group' ); ?>

			<table class="form-table" role="presentation">
				<tbody>
				<?php foreach ( $fields as $key => $field ) : ?>
					<?php $value = isset( $values[ $key ] ) ? $values[ $key ] : ''; ?>
					<tr>
						<th scope="row">
							<label for="magenta-<?php echo esc_attr( $key ); ?>">
								<?php echo esc_html( $field['label'] ); ?>
							</label>
						</th>
						<td>
							<?php if ( 'textarea' === $field['type'] ) : ?>
								<textarea id="magenta-<?php echo esc_attr( $key ); ?>"
									name="<?php echo esc_attr( MAGENTA_BUSINESS_OPTION ); ?>[<?php echo esc_attr( $key ); ?>]"
									rows="4" class="large-text code"><?php echo esc_textarea( $value ); ?></textarea>
							<?php else : ?>
								<input type="<?php echo esc_attr( $field['type'] ); ?>"
									id="magenta-<?php echo esc_attr( $key ); ?>"
									name="<?php echo esc_attr( MAGENTA_BUSINESS_OPTION ); ?>[<?php echo esc_attr( $key ); ?>]"
									value="<?php echo esc_attr( $value ); ?>"
									class="regular-text">
							<?php endif; ?>
							<p class="description"><?php echo esc_html( $field['hint'] ); ?></p>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<?php submit_button( __( 'Save business details', 'magenta' ) ); ?>
		</form>
	</div>
	<?php
}
