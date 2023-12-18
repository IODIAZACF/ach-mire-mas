<?php

// where to get files from
const ENTRY_FIELD = array('filepond');

// where to write files to
const TRANSFER_DIR 	= __DIR__ . '/tmp/';
const UPLOAD_DIR 	= __DIR__ . '/images/';
const DATABASE_FILE = __DIR__ . '/database.json';

// name to use for the file metadata object
const METADATA_FILENAME = __DIR__ . '/metadata';

// this automatically creates the upload and transfer directories, if they're not there already
if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0777);
if (!is_dir(TRANSFER_DIR)) mkdir(TRANSFER_DIR, 0777);