FROM node:20-alpine
WORKDIR /app
# we'll run npm i from inside container (volume), keeping Dockerfile simple
EXPOSE 3001
