# Interactivity API Demo - WP Movies

## What is this?

This is a demo plugin which shows the features of the Interactivity API for
WordPress (TODO: `link to the blog post in Make Core`).

It can be installed as a WordPress plugin that creates a site similar to (TODO: `insert URL when have a production site`)

The plugin is split into `src/blocks` and `lib` folders:

- `src/blocks` - The blocks that use the Interactivity API in interactive
  blocks. **This is the part that you should start with** in order to understand
  how to build interactive blocks with the Interactivity API.
- `/lib` - The code that contains the runtime and internals of the Interactivity API (which
  will eventually be part of Gutenberg) and the configuration needed to run the demo.

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

1. Install the dependencies

    ```sh
    npm install && composer install
    ```

2. Build the plugin

    ```sh
    npm start
    ```

3. Run the preconfigured local WordPress environment using
   [`wp-env`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/)
   (recommended). This will require you to have
   [Docker](https://www.docker.com/) running.

   ```sh
   npx wp-env start
   ```

4. Activate the WP Movies plugin and the WP Movies theme by running:

    ```sh
    npx wp-env run cli "wp theme activate wp-movies-theme"
    npx wp-env run cli "wp plugin activate wp-movies-demo"
    ```

5. Go to the admin (user: `admin`, password: `password`) and import the Movie data into the WordPress:
    1. Go to **Tools > Import > WordPress** and click on _Run Importer_.
    2. Select the `wp_sampledata_movies.xml` file.
    3. Select the `Download and import file attachments` and click on the
       `Upload file and import`.
    4. Repeat the process for the `wp_sampledata_actors.xml` file.

    This process will also download the images for all the movies. If you run into any
    problems you can run `npx wp-env clean all` and start this step over again.

6. Set the permalinks to use the `Post name` in **Settings > Permalinks**.
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

## Credits

Powered by [WordPress](https://wordpress.org/) and [TMDb](https://www.themoviedb.org/).
