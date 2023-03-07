const fs = require('fs');
const archiver = require('archiver');

// Create a new zip file
const output = fs.createWriteStream('wpmovies-theme.zip');
const archive = archiver('zip', { zlib: { level: 9 } });

// Add files and directories to the zip file
archive.directory('wp-movies-theme/', 'wpmovies-theme');

// Finalize the zip file
archive.pipe(output);
archive.finalize();