#!/bin/bash
docker container run --rm --network=host -e SONAR_HOST_URL="http://localhost:9000" -v "./web/public:/var/www/html/public" sonarsource/sonar-scanner-cli -Dsonar.projectKey=php-test \
-Dsonar.sources=. \
-Dsonar.host.url=http://localhost:9000 \
-Dsonar.login=sqp_c470ca618cd74d6905c530adf83bf71232dc23c0