# Interactivity API Demo - WP Movies

🎥 Live demo: [wpmovies.dev](https://wpmovies.dev)


https://user-images.githubusercontent.com/5417266/228688653-4af8bbae-0360-468e-a662-1a3998e62cb6.mp4

## WARNING - CODE IS CURRENTLY BROKEN (see below, Setup)

## What is this?

This is a demo plugin which shows the features of the [Interactivity API](https://github.com/WordPress/block-interactivity-experiments) for
WordPress proposed in [this post in Make WordPress Core blog](https://make.wordpress.org/core/2023/03/30/proposal-the-interactivity-api-a-better-developer-experience-in-building-interactive-blocks/).

The plugin is split into:

-   `/src/blocks/interactive` - **Start here to understand how to build interactive blocks with the
    Interactivity API**. The folder contains all the custom interactive blocks used in the
    demo.

-   `/lib` - The code that contains the runtime and internals of the Interactivity
    API and the configuration needed to run the demo.

-   `/wp-movies-theme` - The custom theme used in the demo. Contains some custom
    styling and the templates for the header & footer as well as the movie &
    actors pages.

## When will I be able to use the Interactivity API?

The [Interactivity API](https://github.com/WordPress/block-interactivity-experiments) is an **experimental feature** and not ready for adoption yet.
It is under active development and its final public API is **very likely going to change before an official release**.

## Setup

> ⚠️ **WARNING**: These instructions don't work anymore because there's a conflict between the Interactivity API shipped in the Block Interactivity Experiments plugin and the Interactivity API shipped now in Gutenberg. We'll migrate this repo to use only Gutenberg, but in the meantime, please use this [Getting Started guide](https://github.com/WordPress/gutenberg/blob/trunk/packages/interactivity/docs/1-getting-started.md) if you want to test the Interactivity API. If you have questions, you can open a discussion in the [Interactivity API category](https://github.com/WordPress/gutenberg/discussions/categories/interactivity-api) of GitHub.

1. Install the required plugins:

   - If you use [`wp-env`]([url](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/)), run `npx wp-env start` to install the plugins automatically and run a local WP instance.

   - Otherwise, install the following plugins:

     - [Gutenberg](https://github.com/WordPress/gutenberg/releases/latest/download/gutenberg.zip)

     - [Block Interactivity Experiments](https://github.com/WordPress/block-interactivity-experiments/releases/latest/download/block-interactivity-experiments.zip). This one requires Gutenberg to work.

     - [Movies Demo Plugin](https://github.com/WordPress/wp-movies-demo/releases/latest/download/wp-movies-plugin.zip). This one requires the Block Interactivity Experiments to work.


2.  Install the dependencies and build the plugin:
  
    ```sh
    npm install && composer install
    npm run build
    ```
    
    If you plan on tinkering with the frontend code, start the webpack
    server which automatically rebuild the files when you make any changes:
    
    ```
    npm start
    ```

3. Install the theme:

    You need to install and activate the [Movies Demo
    Theme](https://github.com/WordPress/wp-movies-demo/releases/latest/download/wp-movies-theme.zip).
    Again, if you are using `wp-env`, it is already installed by default and you
    just have to activate it. You can run:

    ```sh
      npx wp-env run cli "wp theme activate wp-movies-theme"
    ```

4. Add the movie and actor data to the WordPress database:

    You can import the data manually:

    1. Go to **Tools > Import > WordPress** and click on _Run Importer_.
    2. Select the `wp_sampledata_movies.xml` file.
    3. Select the `Download and import file attachments` and click on the
       `Upload file and import`.
    4. Repeat the process for the `wp_sampledata_actors.xml` file.
    5. Repeat the process for the `wp_sampledata_media.xml` file. This one can take up to five minutes.

    If you are using `wp-env`, you can run the following commands:

    ```sh
      npx wp-env run cli "import wp-content/plugins/wp-movies-demo/wp_sampledata_movies.xml --authors=create"
      npx wp-env run cli "import wp-content/plugins/wp-movies-demo/wp_sampledata_media.xml  --authors=create"
      npx wp-env run cli "import wp-content/plugins/wp-movies-demo/wp_sampledata_actors.xml --authors=create"
    ```

    If you run into any problems you can run `npx wp-env clean all` and start this step over again.

5. Set the permalinks to use the `Post name` in **Settings > Permalinks**.

    If you are using `wp-env` you can just run this command:

    ```sh
    npx wp-env run cli "wp rewrite structure '/%postname%/'"
    ```

6. Change settings to show `8` posts and RSS items per page in **Settings > Reading**
7. Enable the **Client Side Navigations** in the **Settings > WP Directives**.

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
