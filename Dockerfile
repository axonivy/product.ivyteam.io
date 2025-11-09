FROM eclipse-temurin:25.0.1_8-jre-noble

ADD target/product-listing-service*.jar /app.jar

CMD ["java", "-jar", "/app.jar"]
EXPOSE 8080
