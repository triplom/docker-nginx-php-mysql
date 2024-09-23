#!/bin/bash
docker container run --rm --network=host -e SONAR_HOST_URL="http://localhost:9000" -v "./web/app:/app" sonarsource/sonar-scanner-cli -Dsonar.projectKey=php-test \
-Dsonar.sources=. \
-Dsonar.host.url=http://localhost:9000 \
-Dsonar.login=sqp_4a53ac56aa9e5c32ab67e92fedac8dffe856636b