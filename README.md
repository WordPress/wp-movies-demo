# Interactivity API Demo - WP Movies

## What is this?

This is a demo plugin which shows the features of the Interactivity API for WordPress (`link to the blog post in Make Core`).

It can be installed as a WordPress plugin that creates a site similar to `insert
URL when have a production site`.

The plugin is split into `src/blocks` and `lib` folders:

- `src/blocks` - The blocks that use the Interactivity API and show how to use
  it build interactive blocks.
- `/lib` - The code that contains the runtime of the Interactivity API (which
  will eventually be part of Gutenberg) and the configuration needed to run the demo.

## Prerequisites

- [Docker](https://www.docker.com/)
- node.js
- composer

## Setup

1. Install the dependencies

    ```
    npm install && composer install
    ```
2. Build the plugin

    ```
    npm start
    ```

3. Run the preconfigured local WordPress environment using
   [`wp-env`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/)
   (recommended). This will require you to have
   [Docker](https://www.docker.com/) running.

   ```
   npx wp-env start
   ```
   
4. Activate the WP Movies plugin in the WordPress admin.
5. Activate the WP Movies theme.
6. Import the Movie data into the WordPress database:
    1. Go to **Tools > Import > WordPress** and click on _Run Importer_.
    2. Select the `wp_sampledata_movies.xml` file.
    3. Select the `Download and import file attachments` and click on the
       `Upload file and import`.
    4. Repeat the process for the `wp_sampledata_actors.xml` file.
    
    This process will also download the images for all the movies. If you run into any 
    problems you can run `npx wp-env clean all` and start this step over again.
    
7. You should set the permalinks to use the `Post name` in **Settings > Permalinks**.
8. Change settings to show `8` posts/RSS items per page in **Settings > Reading**
9. Enable the **Client Side Navigations** in the **Settings > WP Directives**.

## Things to try

#### Client-side Navigations

When enabled, the lists of movies and actors will paginate without doing a full
page refresh. You can enable this behavior in your WordPress admin page in
**Settings > WP Directives**.

#### Add to favourites
You can add a movie to favourites and notice how the numbers of movie likes is
preserved when using client-side navigations.

#### Instant search
Try searching for movies or actors. The search results are rendered dynamically
on the server!

## Credits

Powered by [WordPress](https://wordpress.org/) and [TMDb](https://www.themoviedb.org/).