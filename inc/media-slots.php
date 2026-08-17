<?php
/**
 * Image slots - the no-ACF, no-paid-plugin content layer for photography.
 *
 * Every photograph on the site is a named slot. A slot knows the crop it needs
 * and the shot it is asking for. Until an attachment is assigned it renders a
 * halftone placeholder carrying its own spec, so the page reads as designed
 * rather than broken, and Barbara can see exactly what is still missing.
 *
 * The registry below is simultaneously:
 *   - the render contract used by magenta_slot_image()
 *   - the admin UI at Appearance > Magenta Media
 *   - the Photo Brief handed to whoever is shooting
 *
 * One source of truth. Edit the array, everything downstream follows.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const MAGENTA_MEDIA_OPTION = 'magenta_media';

/**
 * The slot registry.
 *
 * group  - grouping in the admin screen and the brief
 * label  - human name of the slot
 * ratio  - crop the theme will apply
 * size   - registered image size used to render it
 * spec   - the shooting instruction, verbatim, for the photographer
 * min    - minimum pixel dimensions to hand over
 *
 * @return array<string, array<string, string>>
 */
function magenta_slots(): array {
	$slots = array(

		/* ---------------------------------------------------------- Hero */
		'hero_main'          => array(
			'group' => 'Hero',
			'label' => 'Hero - primary image',
			'ratio' => '4x5',
			'size'  => 'magenta-4x5',
			'min'   => '2400 x 3000px',
			'spec'  => 'Macro of the roller loaded with magenta ink - wet, viscous, catching a hard side light. Shoot tight enough that the ink reads as texture, not as machinery. Leave empty space on the left third: the headline sits there.',
		),
		'hero_press'         => array(
			'group' => 'Hero',
			'label' => 'Hero - press sheet in motion',
			'ratio' => '16x9',
			'size'  => 'magenta-16x9',
			'min'   => '3200 x 1800px',
			'spec'  => 'A printed sheet coming off the press, shot with a slow enough shutter that the movement smears. Blur is the point - do not freeze it. Overhead or three-quarter angle.',
		),

		/* ------------------------------------------------------ Services */
		'service_offset'     => array(
			'group' => 'Services',
			'label' => 'Offset printing',
			'ratio' => '1x1',
			'size'  => 'magenta-1x1',
			'min'   => '1800 x 1800px',
			'spec'  => 'Stack of freshly cut sheets, edge on, so the layers read as lines. Neutral background, hard light, strong shadow.',
		),
		'service_large'      => array(
			'group' => 'Services',
			'label' => 'Large format',
			'ratio' => '1x1',
			'size'  => 'magenta-1x1',
			'min'   => '1800 x 1800px',
			'spec'  => 'Installed vinyl or a banner in a real Cayman location - a hotel lobby, a restaurant window. Shot straight on, daylight.',
		),
		'service_screen'     => array(
			'group' => 'Services',
			'label' => 'Screen printing',
			'ratio' => '1x1',
			'size'  => 'magenta-1x1',
			'min'   => '1800 x 1800px',
			'spec'  => 'The squeegee mid-pull across the screen, hands in frame. Shoot a burst and pick the frame where the ink is spreading.',
		),
		'service_finishing'  => array(
			'group' => 'Services',
			'label' => 'Foil and finishing',
			'ratio' => '1x1',
			'size'  => 'magenta-1x1',
			'min'   => '1800 x 1800px',
			'spec'  => 'Detail of hot foil or embossing catching the light. Tilt the stock until the foil flares. Dark background so the shine separates.',
		),
		'service_packaging'  => array(
			'group' => 'Services',
			'label' => 'Packaging',
			'ratio' => '1x1',
			'size'  => 'magenta-1x1',
			'min'   => '1800 x 1800px',
			'spec'  => 'A flat, unfolded die-cut box shot dead overhead on white, plus the same box assembled. Two frames, same lighting.',
		),
		'service_signage'    => array(
			'group' => 'Services',
			'label' => 'Signage',
			'ratio' => '1x1',
			'size'  => 'magenta-1x1',
			'min'   => '1800 x 1800px',
			'spec'  => 'Cut acrylic or dimensional letters mounted on a wall, raking light so the letters throw shadows.',
		),

		/* --------------------------------------------------------- About */
		'about_portrait'     => array(
			'group' => 'About',
			'label' => 'Barbara - working',
			'ratio' => '4x5',
			'size'  => 'magenta-4x5',
			'min'   => '2400 x 3000px',
			'spec'  => 'Barbara at the press or over a proof, in profile, natural side light. Candid - she should be looking at the work, not the camera.',
		),
		'about_cutout'       => array(
			'group' => 'About',
			'label' => 'Barbara - cut-out',
			'ratio' => 'cut',
			'size'  => 'magenta-cut',
			'min'   => '2400px long edge',
			'spec'  => 'Barbara looking straight down the lens, shot against a plain white wall or seamless, even light, full or half body. This one gets cut out and used as a sticker, so keep the edges clean and do not crop limbs at the frame.',
		),
		'about_studio'       => array(
			'group' => 'About',
			'label' => 'The studio',
			'ratio' => '16x9',
			'size'  => 'magenta-16x9',
			'min'   => '3200 x 1800px',
			'spec'  => 'Wide of the workshop with machines running and people in it. Slightly messy is better than staged - this is the proof that the work is real.',
		),

		/* -------------------------------------------------------- Detail */
		'texture_paper'      => array(
			'group' => 'Textures',
			'label' => 'Paper texture',
			'ratio' => '1x1',
			'size'  => 'magenta-1x1',
			'min'   => '3000 x 3000px',
			'spec'  => 'Top-down, 90 degrees, flat even light, no shadow: uncoated stock filling the whole frame. This is used as a background texture across the site, so it must be perfectly parallel and evenly lit. Shoot three stocks: white uncoated, kraft, and a coloured sheet.',
		),
		'texture_swatches'   => array(
			'group' => 'Textures',
			'label' => 'Pantone swatches',
			'ratio' => 'cut',
			'size'  => 'magenta-cut',
			'min'   => '2400px long edge',
			'spec'  => 'Pantone book fanned open on white, shot overhead. Fan it so magenta and the process colours are the ones showing.',
		),

		/* ---------------------------------------------------------- Close */
		'cta_background'     => array(
			'group' => 'Closing',
			'label' => 'Closing section background',
			'ratio' => '16x9',
			'size'  => 'magenta-16x9',
			'min'   => '3200 x 1800px',
			'spec'  => 'Hands holding a finished piece - a letterpress card, a menu - close enough that you can see the bite in the paper. Shallow depth of field.',
		),
	);

	/**
	 * Filter the slot registry.
	 *
	 * @param array $slots Slot definitions.
	 */
	return apply_filters( 'magenta_slots', $slots );
}

/**
 * Assigned attachment IDs, keyed by slot.
 */
function magenta_media_map(): array {
	$map = get_option( MAGENTA_MEDIA_OPTION, array() );
	return is_array( $map ) ? $map : array();
}

/**
 * Attachment ID for a slot, or 0 when the photograph does not exist yet.
 */
function magenta_slot_id( string $slot ): int {
	$map = magenta_media_map();
	return isset( $map[ $slot ] ) ? (int) $map[ $slot ] : 0;
}

/**
 * How many slots are still waiting on a photograph.
 */
function magenta_slots_pending(): int {
	$pending = 0;
	foreach ( array_keys( magenta_slots() ) as $slot ) {
		if ( ! magenta_slot_id( $slot ) ) {
			++$pending;
		}
	}
	return $pending;
}

/* -------------------------------------------------------------------------
 * Admin screen
 * ---------------------------------------------------------------------- */

function magenta_media_menu(): void {
	$pending = magenta_slots_pending();
	$title   = __( 'Magenta Media', 'magenta' );
	if ( $pending ) {
		$title .= sprintf( ' <span class="awaiting-mod"><span class="pending-count">%d</span></span>', $pending );
	}

	add_theme_page(
		__( 'Magenta Media', 'magenta' ),
		$title,
		'edit_theme_options',
		'magenta-media',
		'magenta_media_screen'
	);
}
add_action( 'admin_menu', 'magenta_media_menu' );

function magenta_media_register_setting(): void {
	register_setting(
		'magenta_media_group',
		MAGENTA_MEDIA_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'magenta_media_sanitize',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'magenta_media_register_setting' );

/**
 * Only known slots, only positive integers, only real attachments.
 */
function magenta_media_sanitize( $input ): array {
	$clean = array();
	if ( ! is_array( $input ) ) {
		return $clean;
	}
	$known = array_keys( magenta_slots() );

	foreach ( $input as $slot => $id ) {
		if ( ! in_array( $slot, $known, true ) ) {
			continue;
		}
		$id = absint( $id );
		if ( $id && 'attachment' === get_post_type( $id ) ) {
			$clean[ $slot ] = $id;
		}
	}
	return $clean;
}

function magenta_media_admin_assets( string $hook ): void {
	if ( 'appearance_page_magenta-media' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script(
		'magenta-admin-media',
		MAGENTA_URI . '/assets/js/admin-media.js',
		array( 'jquery' ),
		MAGENTA_VERSION,
		true
	);
	wp_enqueue_style(
		'magenta-admin-media',
		MAGENTA_URI . '/assets/css/admin.css',
		array(),
		MAGENTA_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'magenta_media_admin_assets' );

function magenta_media_screen(): void {
	$slots   = magenta_slots();
	$map     = magenta_media_map();
	$pending = magenta_slots_pending();
	$total   = count( $slots );
	$done    = $total - $pending;

	$groups = array();
	foreach ( $slots as $key => $slot ) {
		$groups[ $slot['group'] ][ $key ] = $slot;
	}
	?>
	<div class="wrap magenta-media">
		<h1><?php esc_html_e( 'Magenta Media', 'magenta' ); ?></h1>

		<div class="magenta-media__intro">
			<p>
				<?php esc_html_e( 'Every photograph on the site lives in one of the slots below. Each slot states the shot it needs and the crop it will be given. Until an image is assigned, the site shows a halftone placeholder in its place - the page still looks finished, it just tells you what is missing.', 'magenta' ); ?>
			</p>
			<p class="magenta-media__progress">
				<strong><?php echo esc_html( sprintf( '%d / %d', $done, $total ) ); ?></strong>
				<?php esc_html_e( 'slots filled', 'magenta' ); ?>
				<span class="magenta-media__bar" aria-hidden="true">
					<span style="width:<?php echo esc_attr( $total ? round( $done / $total * 100 ) : 0 ); ?>%"></span>
				</span>
			</p>
			<p class="description">
				<?php esc_html_e( 'Shooting notes for the whole set: photograph everything in RAW, hand over both a vertical 4:5 and a horizontal 16:9 frame of each setup, and leave breathing room at the edges - the typography is large and sits over the image. One full pass of every object on a plain white background is required: those become the cut-out stickers the layout is built from.', 'magenta' ); ?>
			</p>
		</div>

		<form method="post" action="options.php">
			<?php settings_fields( 'magenta_media_group' ); ?>

			<?php foreach ( $groups as $group_name => $group_slots ) : ?>
				<h2 class="magenta-media__group"><?php echo esc_html( $group_name ); ?></h2>
				<div class="magenta-media__grid">
					<?php
					foreach ( $group_slots as $key => $slot ) :
						$id  = isset( $map[ $key ] ) ? (int) $map[ $key ] : 0;
						$src = $id ? wp_get_attachment_image_url( $id, 'medium' ) : '';
						?>
						<div class="magenta-slot <?php echo $id ? 'is-filled' : 'is-empty'; ?>" data-slot="<?php echo esc_attr( $key ); ?>">
							<div class="magenta-slot__preview" data-preview>
								<?php if ( $src ) : ?>
									<img src="<?php echo esc_url( $src ); ?>" alt="">
								<?php else : ?>
									<span class="magenta-slot__ph"><?php echo esc_html( strtoupper( str_replace( '_', ' ', $key ) ) ); ?></span>
								<?php endif; ?>
							</div>

							<div class="magenta-slot__body">
								<h3><?php echo esc_html( $slot['label'] ); ?></h3>
								<p class="magenta-slot__meta">
									<code><?php echo esc_html( $slot['ratio'] ); ?></code>
									<span><?php echo esc_html( $slot['min'] ); ?></span>
								</p>
								<p class="magenta-slot__spec"><?php echo esc_html( $slot['spec'] ); ?></p>

								<input type="hidden"
									name="<?php echo esc_attr( MAGENTA_MEDIA_OPTION ); ?>[<?php echo esc_attr( $key ); ?>]"
									value="<?php echo esc_attr( $id ); ?>"
									data-input>

								<p class="magenta-slot__actions">
									<button type="button" class="button" data-select><?php esc_html_e( 'Choose image', 'magenta' ); ?></button>
									<button type="button" class="button-link magenta-slot__remove" data-remove <?php disabled( ! $id ); ?>><?php esc_html_e( 'Remove', 'magenta' ); ?></button>
								</p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>

			<?php submit_button( __( 'Save media', 'magenta' ) ); ?>
		</form>
	</div>
	<?php
}
