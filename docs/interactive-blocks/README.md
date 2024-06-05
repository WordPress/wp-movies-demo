# Overview of the interactive blocks

For the functionality of the likes, we created several blocks that work together.

## Likes Number

### `render.php`

```php
<?php
$wrapper_attributes = get_block_wrapper_attributes();
$play_icon          = file_get_contents( get_template_directory() . '/assets/empty-heart.svg' );

wp_interactivity_state(
	'wpmovies',
	array(
		'likedMovies'           => array(),
		'isLikedMoviesNotEmpty' => false,
	),
);
?>

<div
	data-wp-interactive="wpmovies"
	<?php echo $wrapper_attributes; ?>
	data-wp-class--wpmovies-liked="state.isLikedMoviesNotEmpty"
>
	<?php echo $play_icon; ?>
	<span data-wp-text="state.likedMovies.length"></span>
</div>
```

We create an initial array to save the IDs of the favorite movies.

_For this demo, we are not saving that data in the database, but if we were, that array would be populated with the list of favorite movies of a logged-in user._

In the `render.php` file, we populate the state with the array and the initial state of a derived state which tell us if there is at least one favorite movie (`state.isLikedMoviesNotEmpty`).

In the HTML, we use the `data-wp-class` directive to add or remove the `wpmovies-liked` class dynamically according to the value of the `state.isLikedMoviesNotEmpty` reference. We also use a `data-wp-text` directive to display the number of favorite movies.

On the server, the references of each directive will be evaluated and the HTML will be modified accordingly.

### `view.js`

```js
const { state } = store( 'wpmovies', {
	state: {
		get isLikedMoviesNotEmpty() {
			return state.likedMovies.length > 0;
		},
	},
} );
```

In the `view.js` file, we add the logic of the derived state that depend on the other parts of the state. After hydration, the HTML will be modified reactively when any of the values of those references change.

## Movie Like Button and Movie Like Icon

### `render.php`

```php
<?php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
$play_icon          = file_get_contents( get_template_directory() . '/assets/empty-heart.svg' );
$context            = array( 'post' => array( 'id' => $post->ID ) );

wp_interactivity_state(
	'wpmovies',
	array(
		'isMovieIncluded' => false,
	),
);
?>

<div
	data-wp-interactive="wpmovies"
	<?php echo $wrapper_attributes; ?>
	<?php echo wp_interactivity_data_wp_context( $context ); ?>
>
	<div
		class="wpmovies-page-button-parent"
		data-wp-on--click="actions.toggleMovie"
	>
		<div
			class="wpmovies-page-button-child"
			data-wp-class--wpmovies-liked="state.isMovieIncluded"
		>
			<?php echo $play_icon; ?>
			<span>
				<?php _e( 'Like', 'wp-movies-demo' ); ?>
			</span>
		</div>
	</div>
</div>
```

First, we add the initial value of a derived state that tells us if this movie is favorite or not to the store.

_In this case, that initial value is always false, but if we were storing the favorite movies in the database, it would be populated with the correct value._

In the HTML, we use a `data-wp-context` directive in which we inject the post id from the server using the `wp_interactivity_data_wp_context` helper. We also add an event handler for the click event and use `data-wp-class` to add a class depending on the derived state value.

It is also worth noting that we can use the PHP `_e( 'Like' )` function to translate parts of the HTML without any problem.

### `view.js`

```js
const { state } = store( 'wpmovies', {
	state: {
		get isMovieIncluded() {
			const ctx = getContext();
			return state.likedMovies.includes( ctx.post.id );
		},
	},
	actions: {
		toggleMovie: () => {
			const ctx = getContext();
			const index = state.likedMovies.findIndex(
				( post ) => post === ctx.post.id
			);
			if ( index === -1 ) state.likedMovies.push( ctx.post.id );
			else state.likedMovies.splice( index, 1 );
		},
	},
} );
```

In the `view.js` file, we add both the derived state, which reads the post ID from the context and returns whether that post ID is present or not in the general array (`state.likedMovies`), and an event handler that toggles that post ID in the general array.

## Movie tabs

### `render.php`

```php
<?php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes(
	array( 'class' => 'wpmovies-tabs' )
);

$images = json_decode( get_post_meta( $post->ID, '_wpmovies_images', true ), true );
$videos = json_decode( get_post_meta( $post->ID, '_wpmovies_videos', true ), true );

wp_interactivity_state(
	'wpmovies',
	array(
		'isImagesTab' => true,
		'isVideosTab' => false,
	),
);
?>

<div
	data-wp-interactive="wpmovies"
	<?php echo $wrapper_attributes; ?>
	data-wp-context='{ "tab": "images" }'
>
	<ul role="tablist">
		<li class="wpmovies-tabs-title">
			<button
				id="wpmovies-images-tab"
				data-wp-on--click="actions.showImagesTab"
				data-wp-class--wpmovies-active-tab="state.isImagesTab"
				data-wp-bind--aria-selected="state.isImagesTab"
				role="tab"
				class="wpmovies-tab-button"
			>
			Images
			</button>
		</li>
		<li class="wpmovies-tabs-title">
			<button
				id="wpmovies-videos-tab"
				data-wp-on--click="actions.showVideosTab"
				data-wp-class--wpmovies-active-tab="state.isVideosTab"
				data-wp-bind--aria-selected="state.isVideosTab"
				role="tab"
				class="wpmovies-tab-button"
			>
			Videos
			</button>
		</li>
	</ul>

	<div
		role="tabpanel"
		data-wp-bind--hidden="state.isVideosTab"
		data-wp-bind--aria-hidden="state.isVideosTab"
		aria-labelledby="wpmovies-images-tab"
	>
		<div class="wpmovies-media-scroller wpmovies-images-tab">
			<?php
			foreach ( $images as $image_id ) {
				$image_url = wp_get_attachment_image_url( $image_id, '' );
				?>
				<img src="<?php echo $image_url; ?>">
				<?php
			}
			?>
		</div>
	</div>

	<div
		role="tabpanel"
		data-wp-bind--hidden="state.isImagesTab"
		data-wp-bind--aria-hidden="state.isImagesTab"
		aria-labelledby="wpmovies-videos-tab"
	>
		<div class="wpmovies-media-scroller wpmovies-videos-tab">
			<?php
			foreach ( $videos as $video ) {
				$video_id = substr( $video['url'], strpos( $video['url'], '?v=' ) + 3 );
				?>
				<div class="wpmovies-tabs-video-wrapper" data-wp-context='{ "videoId": "<?php echo $video_id; ?>" }'>
					<div data-wp-on--click="actions.setVideo" aria-controls="wp-movies-video-player">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ffffff" class="play-icon">
							<path d="M3 22v-20l18 10-18 10z" />
						</svg>
					</div>
					<img src="<?php echo 'https://img.youtube.com/vi/' . $video_id . '/0.jpg'; ?>">
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
```

In the `render.php` file, we add the initial value of a couple of derived state values that indicate which tab to display.

_Right now those values are hardcoded, but they could change dynamically depending on some database value, the URL query or the block attributes. Also, this logic could have been abstracted in a single derived state, but we preferred to leave it as it is to make the example easier to understand._

In the HTML, we add a `data-wp-context` directive that controls which tab should be shown, some event handlers to modify that context, and some `data-wp-class` that dynamically adds a class to the tab that is active at each moment.

After the buttons, we use `data-wp-class` to show or hide the content of each tab, depending on which one is active. In addition, the videos have an event handler that calls the `actions.setVideo` action of the Video Player block, to play the trailer in its modal.

### `view.js`

```js
store( 'wpmovies', {
	state: {
		get isImagesTab() {
			const ctx = getContext();
			return ctx.tab === 'images';
		},
		get isVideosTab() {
			const ctx = getContext();
			return ctx.tab === 'videos';
		},
	},
	actions: {
		showImagesTab: () => {
			const ctx = getContext();
			ctx.tab = 'images';
		},
		showVideosTab: () => {
			const ctx = getContext();
			ctx.tab = 'videos';
		},
	},
} );
```

In the `view.js`, we simply add the logic of the derived state that vary depending on the context, and the actions that modify it.

## Video Player and Movie Trailer Button

### `render.php`

```php
<?php
// Movie Trailer Button (simplified)
$context = array( 'videoId' => $trailer_id );
?>
<div
	data-wp-interactive="wpmovies"
	<?php echo $wrapper_attributes; ?>
	<?php echo wp_interactivity_data_wp_context( $context ); ?>
>
	<div
		class="wpmovies-page-button-parent"
		data-wp-on--click="actions.setVideo"
		aria-controls="wp-movies-video-player"
	>
		<div class="wpmovies-page-button-child">
			<?php echo $play_icon; ?>
			<span>Play trailer</span>
		</div>
	</div>
</div>
```

```php
<?php
// Video Player
$wrapper_attributes = get_block_wrapper_attributes(
	array( 'class' => 'wpmovies-video-player' )
);

wp_interactivity_state(
	'wpmovies',
	array(
		'currentVideo' => '',
		'isPlaying'    => false,
	),
);
?>

<div
	<?php echo $wrapper_attributes; ?>
	id="wp-movies-video-player"
	data-wp-interactive="wpmovies"
	data-wp-bind--hidden="!state.isPlaying"
>
	<div class="wpmovies-video-wrapper">
		<div class="wpmovies-video-close">
			<button
				class="close-button"
				data-wp-on--click="actions.closeVideo"
			>
				<?php _e( 'Close', 'wp-movies-demo' ); ?>
			</button>
		</div>
		<iframe
			width="420"
			height="315"
			allow="autoplay"
			allowfullscreen
			data-wp-bind--src="state.currentVideo"
		></iframe>
	</div>
</div>
```

In the `render.php` file of the button, we simply set an event handler that executes the `actions.setVide` action. This action reads the videoId from the context and modifies the `state`.

In the `render.php` file of the video player, we set first the initial value of the video being played and a selector to indicate if there is a video playing or not.

In the HTML, we use a `data-wp-class` directive to show a modal when there is a video playing. That modal contains an `iframe` with the URL of the corresponding YouTube video. There is also a button to close the modal.

### `view.js`

```js
const { state } = store( 'wpmovies', {
	state: {
		get isPlaying() {
			return state.currentVideo !== '';
		},
	},
	actions: {
		closeVideo: () => {
			state.currentVideo = '';
		},
		setVideo: () => {
			const ctx = getContext();
			state.currentVideo =
				'https://www.youtube.com/embed/' + ctx.videoId + '?autoplay=1';
		},
	},
} );
```

In the `view.js` file, we simply add the logic of the derived state value that indicates if there is a video playing or not, and the actions that change the value of the part of the state that contains the video.

## Movie Search

### `render.php`

```php
<?php
$wrapper_attributes = get_block_wrapper_attributes(
	array( 'class' => 'movie-search' )
);

wp_interactivity_state(
	'wpmovies',
	array(
		'searchValue' => get_search_query(),
	),
);
?>

<div
	<?php echo $wrapper_attributes; ?>
	data-wp-interactive="wpmovies"
>
	<form>
		<label class="search-label" for="movie-search">
			<?php _e( 'Search for a movie', 'wp-movies-demo' ); ?>
		</label>
		<input
			id="movie-search"
			type="search"
			name="s"
			role="search"
			inputmode="search"
			placeholder=<?php _e( 'Search for a movie', 'wp-movies-demo' ); ?>
			required=""
			autocomplete="off"
			data-wp-bind--value="state.searchValue"
			data-wp-on--input="actions.updateSearch"
			>
	</form>
</div>
```

This block controls the instant search. No other code was needed.

In the `render.php` file, we simply initialize the initial value of the part of the state where the search value is stored. We populate it using `get_search_query()`, which returns the search when the user navigates with server-side rendering.

Then, in the HTML, we use the `data-wp-bind` directive to update the input value every time `state.searchValue` changes, and `data-wp-on` to add an event handler that will be executed every time the user types in the input.

### `view.js`

```js
const updateURL = async ( value ) => {
	const url = new URL( window.location );
	url.searchParams.set( 'post_type', 'movies' );
	url.searchParams.set( 'orderby', 'name' );
	url.searchParams.set( 'order', 'asc' );
	url.searchParams.set( 's', value );
	const { actions } = await import( '@wordpress/interactivity-router' );
	await actions.navigate( `/${ url.search }${ url.hash }` );
};

const { state } = store( 'wpmovies', {
	actions: {
		*updateSearch() {
			const { ref } = getElement();
			const { value } = ref;

			// Don't navigate if the search didn't really change.
			if ( value === state.searchValue ) return;

			state.searchValue = value;

			if ( value === '' ) {
				// If the search is empty, navigate to the home page.
				const { actions } = yield import(
					'@wordpress/interactivity-router'
				);
				yield actions.navigate( '/' );
			} else {
				// If not, navigate to the new URL.
				yield updateURL( value );
			}
		},
	},
} );
```

In the `view.js` part, we simply set the action that controls the navigation. It contains some custom logic to generate the new URL and uses the `navigate` action of the `@wordpress/interactivity-router` store to do the navigation in the client.
