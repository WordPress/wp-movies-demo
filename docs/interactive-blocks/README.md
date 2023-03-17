# Overview of the interactive blocks

For the functionality of the likes, we created several blocks that work together.

## Likes Number

```php
<?php
// render.php
$wrapper_attributes = get_block_wrapper_attributes();
$play_icon          = file_get_contents( get_template_directory() . '/assets/empty-heart.svg' );
$likedMovies        = array();

store(
	array(
		'state' => array(
			'wpmovies' => array(
				'likedMovies' => $likedMovies,
			),
		),
		'selectors' => array(
			'wpmovies' => array(
				'likesCount'            => count( $likedMovies ),
				'isLikedMoviesNotEmpty' => count( $likedMovies ) > 0,
			),
		),
	),
);
?>

<div
	<?php echo $wrapper_attributes; ?>
	data-wp-class.wpmovies-liked="selectors.wpmovies.isLikedMoviesNotEmpty"
>
	<?php echo $play_icon; ?>
	<span data-wp-text="selectors.wpmovies.likesCount"></span>
</div>
```

We create an initial array to save the IDs of the favorite movies.

_For this demo, we are not saving that data in the database, but if we were, that array would be populated with the list of favorite movies of a logged-in user._

In the `render.php` file, we populate the store with the array and the initial state of a couple of selectors which tell us the number of favorite movies and a boolean that tells us if there is at least one favorite movie.

In the HTML, we use the `data-wp-class` directive to add or remove the `wpmovies-liked` class dynamically according to the value of the `selectors.wpmovies.isLikedMoviesNotEmpty` reference. We also use a `data-wp-text` directive to display the number of favorite movies.

On the server, the references of each directive will be evaluated and the HTML will be modified accordingly.

```js
// view.js
import { store } from "@wordpress/interactivity";

store({
  selectors: {
    wpmovies: {
      likesCount: ({ state }) => state.wpmovies.likedMovies.length,
      isLikedMoviesNotEmpty: ({ state }) =>
        state.wpmovies.likedMovies.length !== 0,
    },
  },
});
```

In the `view.js` file, we add the logic of the selectors that depend on the other parts of the state. After hydration, the HTML will be modified reactively when any of the values of those references change.

## Movie Like Button and Movie Like Icon

```php
<?php
// render.php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
$play_icon          = file_get_contents( get_template_directory() . '/assets/empty-heart.svg' );

store(
	array(
		'selectors' => array(
			'wpmovies' => array(
				'isMovieIncluded' => false,
			),
		),
	),
);
?>

<div
	<?php echo $wrapper_attributes; ?>
	data-wp-context='{ "post": { "id": <?php echo $post->ID; ?> } }'
>
	<div
		class="wpmovies-page-button-parent"
		data-wp-on.click="actions.wpmovies.toggleMovie"
	>
		<div
			class="wpmovies-page-button-child"
			data-wp-class.wpmovies-liked="selectors.wpmovies.isMovieIncluded"
		>
			<?php echo $play_icon; ?>
			<span>
				<?php _e( 'Like' ); ?>
			</span>
		</div>
	</div>
</div>
```

First, we add the initial value of a selector that tells us if this movie is favorite or not to the store.

_In this case, that initial value is always false, but if we were storing the favorite movies in the database, it would be populated with the correct value._

In the HTML, we use a `data-wp-context` directive in which we inject the post id from the server. We also add an event handler for the click event and use `data-wp-class` to add a class depending on the selector value.

It is also worth noting that we can use the PHP `_e( 'Like' )` function to translate parts of the HTML without any problem.

```js
// view.js
import { store } from "@wordpress/interactivity";

store({
  selectors: {
    wpmovies: {
      isMovieIncluded: ({ state, context: { post } }) =>
        state.wpmovies.likedMovies.includes(post.id),
    },
  },
  actions: {
    wpmovies: {
      toggleMovie: ({ state, context }) => {
        const index = state.wpmovies.likedMovies.findIndex(
          (post) => post === context.post.id
        );
        if (index === -1) state.wpmovies.likedMovies.push(context.post.id);
        else state.wpmovies.likedMovies.splice(index, 1);
      },
    },
  },
});
```

In the `view.js` file, we add both the selector, which reads the post ID from the context and returns whether that post ID is present or not in the general array (`state.wpmovies.likedMovies`), and an event handler that toggles that post ID in the general array.

## Movie tabs

```php
<?php
// render.php (simplified)
// ...

store(
	array(
		'selectors' => array(
			'wpmovies' => array(
				'isImagesTab' => true,
				'isVideosTab' => false,
			),
		),
	),
);
?>

<div
	<?php echo $wrapper_attributes; ?>
	data-wp-context='{ "tab": "images" }'
>
	<ul>
		<li
			data-wp-on.click="actions.wpmovies.showImagesTab"
			data-wp-class.wpmovies-active-tab="selectors.wpmovies.isImagesTab"
		>
				Images
			</li>
		<li
			data-wp-on.click="actions.wpmovies.showVideosTab"
			data-wp-class.wpmovies-active-tab="selectors.wpmovies.isVideosTab"
		>
				Videos
			</li>
	</ul>

	<div data-wp-show="selectors.wpmovies.isImagesTab">
		<div class="wpmovies-media-scroller wpmovies-images-tab">
			<?php
			foreach ( json_decode( $images, true ) as $image_id ) {
				$image_url = wp_get_attachment_image_url( $image_id, '' );
				?>
				<img src="<?php echo $image_url; ?>">
				<?php
			}
			?>
		</div>
	</div>

	<div data-wp-show="selectors.wpmovies.isVideosTab">
		<div class="wpmovies-media-scroller wpmovies-videos-tab">
			<?php
			foreach ( json_decode( $videos, true ) as $video ) {
				$video_id = substr( $video['url'], strpos( $video['url'], '?v=' ) + 3 );
				?>
				<div class="wpmovies-tabs-video-wrapper" data-wp-context='{ "videoId": "<?php echo $video_id; ?>" }'>
					<div data-wp-on.click="actions.wpmovies.setVideo"><svg ...></div>
					<img src="<?php echo 'https://img.youtube.com/vi/' . $video_id . '/0.jpg'; ?>">
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
```

In the `render.php` file, we add the initial value of a couple of selectors that indicate which tab to display.

_Right now those values are hardcoded, but they could change dynamically depending on some database value, or maybe on the block attributes. Also, this logic could have been abstracted in a single selector, but we preferred to leave it as it is to make the example easier to understand._

In the HTML, we add a `data-wp-context` directive that controls which tab should be shown, some event handlers to modify that context, and some `data-wp-class` that dynamically adds a class to the tab that is active at each moment.

After the buttons, we use `data-wp-show` to show or hide the content of each tab, depending on which one is active. In addition, the videos have an event handler that calls the `actions.wpmovies.setVideo` action of the Video Player block, to play the trailer in its modal.

```js
// view.js
import { store } from "@wordpress/interactivity";

store({
  selectors: {
    wpmovies: {
      isImagesTab: ({ context }) => context.tab === "images",
      isVideosTab: ({ context }) => context.tab === "videos",
    },
  },
  actions: {
    wpmovies: {
      showImagesTab: ({ context }) => {
        context.tab = "images";
      },
      showVideosTab: ({ context }) => {
        context.tab = "videos";
      },
    },
  },
});
```

In the `view.js`, we simply set the selectors that vary depending on the context, and the actions that modify it.

## Video Player and Movie Trailer Button

```php
<?php
// Movie Trailer Button
// render.php (simplified)
<div <?php echo $wrapper_attributes; ?> data-wp-context='{ "videoId": "<?php echo $trailer_id; ?>" }'>
	<div class="wpmovies-page-button-parent" data-wp-on.click="actions.wpmovies.setVideo">
		<div class="wpmovies-page-button-child">
			<?php echo $play_icon; ?><span>Play trailer</span>
		</div>
	</div>
</div>
```

```php
<?php
// Video Player
// render.php (simplified)
store(
	array(
		'state'     => array(
			'wpmovies' => array(
				'currentVideo' => '',
			),
		),
		'selectors' => array(
			'wpmovies' => array(
				'isPlaying' => false,
			),
		),
	),
);
?>

<div data-wp-show="selectors.wpmovies.isPlaying" <?php echo $wrapper_attributes; ?>>
	<div class="wpmovies-video-wrapper">
		<div class="wpmovies-video-close">
			<button class="close-button" data-wp-on.click="actions.wpmovies.closeVideo">
				<?php _e( 'Close' ); ?>
			</button>
		</div>
		<iframe
			width="420"
			height="315"
			allow="autoplay"
			allowfullscreen
			wp-bind:src="state.wpmovies.currentVideo"
		></iframe>
	</div>
</div>
```

In the `render.php` file of the button, we simply set an event handler that executes the `actions.wpmovies.setVide` action. This action reads the videoId from the context and modifies the `state`.

In the `render.php` file of the video player, we set first the initial value of the video being played and a selector to indicate if there is a video playing or not.

In the HTML, we use a `data-wp-show` directive to show a modal when there is a video playing. That modal contains an `iframe` with the URL of the corresponding YouTube video. There is also a button to close the modal.

```js
// view.js
import { store } from "@wordpress/interactivity";

store({
  selectors: {
    wpmovies: {
      isPlaying: ({ state }) => state.wpmovies.currentVideo !== "",
    },
  },
  actions: {
    wpmovies: {
      closeVideo: ({ state }) => {
        state.wpmovies.currentVideo = "";
      },
      setVideo: ({ state, context }) => {
        state.wpmovies.currentVideo =
          "https://www.youtube.com/embed/" + context.videoId + "?autoplay=1";
      },
    },
  },
});
```

In the `view.js` file, we simply set a selector to know if there is a video playing or not, and the actions that change the value of the part of the state that contains the video.

## Movie Search

```php
<?php
// render.php
$wrapper_attributes = get_block_wrapper_attributes(
	array( 'class' => 'movie-search' )
);

store(
	array(
		'state' => array(
			'wpmovies' => array(
				'searchValue' => get_search_query(),
			),
		),
	),
);
?>

<div <?php echo $wrapper_attributes; ?>>
	<input
	  type="search"
	  name="s"
	  inputmode="search"
	  placeholder="Search for a movie..."
		required=""
		data-wp-bind.value="state.wpmovies.searchValue"
		data-wp-on.input="actions.wpmovies.updateSearch"
	>
</div>
```

This block controls the instant search. No other code was needed.

In the `render.php` file, we simply initialize the initial value of the part of the state where the search value is stored. We populate it using `get_search_query()`, which returns the search when the user navigates with server-side rendering.

Then, in the HTML, we use the `data-wp-bind` directive to update the input value every time `state.wpmovies.searchValue` changes, and `data-wp-on` to add an event handler that will be executed every time the user types in the input.

```js
// view.js
import { store } from "@wordpress/interactivity";

const updateURL = async (value) => {
  const url = new URL(window.location);
  url.searchParams.set("post_type", "movies");
  url.searchParams.set("orderby", "name");
  url.searchParams.set("order", "asc");
  url.searchParams.set("s", value);
  await navigate(`/${url.search}${url.hash}`);
};

store({
  actions: {
    wpmovies: {
      updateSearch: async ({ state, event }) => {
        const { value } = event.target;

        // Don't navigate if the search didn't really change.
        if (value === state.wpmovies.searchValue) return;

        state.wpmovies.searchValue = value;

        if (value === "") {
          // If the search is empty, navigate to the home page.
          await navigate("/");
        } else {
          // If not, navigate to the new URL.
          await updateURL(value);
        }
      },
    },
  },
});
```

In the `view.js` part, we simply set the action that controls the navigation. It contains some custom logic to generate the new URL and uses the `navigate` function to do the navigation in the client.

_Although it is far from the final version, the `navigate` function is prepared to do a fallback to server-side navigation in case client-side navigation is not enabled._