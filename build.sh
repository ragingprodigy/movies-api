#!/usr/bin/env bash
echo "$1" > ./VERSION

docker build -t ragingprodigy/ragingprodigy/movie-api:"$1" .

git commit -am"Built Image Version $1"

docker tag ragingprodigy/ragingprodigy/movie-api:"$1" ragingprodigy/ragingprodigy/movie-api:latest

docker push ragingprodigy/ragingprodigy/movie-api:"$1"
docker push ragingprodigy/ragingprodigy/movie-api:latest

git push

echo "Latest Image Version:" $1
