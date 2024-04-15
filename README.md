## About the project

This is a simple app with screen and web-camera capture and recording, that uploads the video captures in chunks, has pausing, and upload cancel, and that uses Laravel broadcast events to track encoding on the backend, and Laravel echo on the inertia's frontend.

## Leverages
* Docker compose.
* Inertia
* Makefile
* Vue with Inertia
* Tailwind

##
* Run make install once the project is running
* Creating a laravel storage link is required
* Running migrations is required
* Running the queue worker is required
