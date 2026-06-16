# Stage 1: get Composer dependencies (if any) in a build stage.
# Multi-stage builds keep the final image small and free of build tools.
FROM composer:2 AS build
WORKDIR /app
COPY . .
# No external dependencies yet, but this is where they would be installed.
RUN composer install --no-dev --no-interaction --optimize-autoloader || true

# Stage 2: the actual runtime image, kept minimal.
FROM php:8.3-cli-alpine AS runtime

# Create a non-root user to run the service.
# Running as root inside a container is a security risk: if the app is
# compromised, the attacker has root in the container. A non-root user limits that.
RUN addgroup -S appgroup && adduser -S appuser -G appgroup

WORKDIR /app

# Copy the application code from the build stage.
COPY --from=build /app /app

# Switch to the non-root user.
USER appuser

# The service listens on port 8080.
EXPOSE 8080

# Start PHP's built-in web server, serving our index.php.
CMD ["php", "-S", "0.0.0.0:8080", "index.php"]
