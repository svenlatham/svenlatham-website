FROM docker.io/nginx:stable-alpine
# Copy all the .conf files to the nginx config directory
COPY deploy/default.conf /etc/nginx/conf.d/

# Copy from the build stage
COPY --from=build /build/ /usr/share/nginx/html/
