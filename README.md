# Interactivity API Demo - WP Movies

## What is this?

This is a demo plugin which shows the features of the Interactivity API for
WordPress (TODO: `link to the blog post in Make Core`).

It can be installed as a WordPress plugin that creates a site similar to (TODO: `insert URL when have a production site`)

The plugin is split into:

- `/src/blocks` - **Start here to understand how to build interactive blocks with the
  Interactivity API**. The folder contains all the custom blocks used in the
  demo. The interactive blocks that use the Interactivity API are:

  - [`/src/blocks/favorites-number`](/src/blocks/favorites-number) - Displays
    the number of movies liked.
  - [`/src/blocks/movie-search`](/src/blocks/movie-search) - An interactive movie search block.
  - [`/src/blocks/post-favorite`](/src/blocks/post-favorite) - A block that
    allows the users to like a movie.
  - [`/src/blocks/video-player`](/src/blocks/video-player) - A video player
    block that plays the movie trailers using Picture-in-Picture (PiP).
  - [`/src/blocks/movie-tabs`](/src/blocks/movie-tabs) - A "tabs" block which
    allows the user to switch between different kinds of info about the movie.
  
- `/lib` - The code that contains the runtime and internals of the Interactivity
  API and the configuration needed to run the demo.

- `/wp-movies-theme` - The custom theme used in the demo. Contains some custom
  styling and the templates for the header & footer as well as the movie &
  actors pages.

## When will I be able to use this?

The Interactivity API is an **experimental feature** and not ready for adoption yet.
It is under active development and its final public API is **very likely going to change before an official release**.
For now, it's recommended to experiment with the Interactivity API via this demo. Use this in
your projects at your own risk.

## Prerequisites

- [Docker](https://www.docker.com/)
- node.js
- composer

## Setup

______
##### user: `admin`
##### password: `password`
______

1. Install the dependencies

    ```sh
    npm install && composer install
    ```

2. Build the plugin

    ```sh
    npm run build

    # If you plan on tinkering with the frontend code you start the webpack
    # server which automatically rebuild the files when you make any changes.
    npm start
    ```

3. Run the preconfigured local WordPress environment using
   [`wp-env`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/)
   (recommended). This will require you to have
   [Docker](https://www.docker.com/) running.

   ```sh
   npx wp-env start
   ```

4. Activate the WP Movies theme

    ```sh
    npx wp-env run cli "wp theme activate wp-movies-theme"
    ```

5. Add the movie and actor data to the WordPress database

    ```sh
    npx wp-env run cli "import wp-content/plugins/wp-movies-demo/wp_sampledata_movies.xml --authors=create"
    npx wp-env run cli "import wp-content/plugins/wp-movies-demo/wp_sampledata_media.xml  --authors=create"
    npx wp-env run cli "import wp-content/plugins/wp-movies-demo/wp_sampledata_actors.xml --authors=create"
    ```

   You can also import the data manually (user: `admin`, password: `password`):
    1. Go to **Tools > Import > WordPress** and click on _Run Importer_.
    2. Select the `wp_sampledata_movies.xml` file.
    3. Select the `Download and import file attachments` and click on the
       `Upload file and import`.
    4. Repeat the process for the `wp_sampledata_actors.xml` file.
    5. Repeat the process for the `wp_sampledata_media.xml` file. This one can take up to five minutes.

    If you run into any problems you can run `npx wp-env clean all` and start this step over again.

6. Set the permalinks to use the `Post name` in **Settings > Permalinks**.

    ```sh
    npx wp-env run cli "wp rewrite structure '/%postname%/'"
    ````

7. Change settings to show `8` posts and RSS items per page in **Settings > Reading**
8. Enable the **Client Side Navigations** in the **Settings > WP Directives**.

## Things to try

### Client-side Navigations and pagination

When enabled, the lists of movies and actors will paginate without doing a full
page refresh. You can enable this behavior in your WordPress admin page in
**Settings > WP Directives**. Click around to the next/previous
page of the movies or actors. You the list is loaded without a delay. This is
because the HTML for that page is prefetched ahead of time, and only the
nodes that are different between the current page and the next page are updated.

### Add to favourites

When you add a movie to favourites notice how the number of movie likes is
preserved when navigating to another page. In addition to client-side
navigations, the Interactivity API uses a smart DOM diffing algorithm. This
allows the interactive state of blocks on the current page to be preserved!

### Instant search

Try searching for movies or actors. The search results are rendered dynamically
on the server!

### Remove the Search template

Try opening the site editor and removing the "Search" template. You'll notice
that the Search experience keeps working but that now the Search results look
different. That's because in the absence of the Search template, the Archive
template is being used. The Interactivity API is designed to work with the 
server-rendered markup and Full-site editing.

### Play the movie trailers

When you navigate to the page for a movie, you can play its trailer. If you
have the client-side navigations
[enabled](#client-side-navigations-and-pagination), you'll notice that the
trailer will keep playing as you keep navigating around the site!

## Credits

Powered by [WordPress](https://wordpress.org/) and [TMDb](https://www.themoviedb.org/).
