FROM eclipse-temurin:25.0.2_10-jre-noble

ADD target/product-listing-service*.jar /app.jar

CMD ["java", "-jar", "/app.jar"]
EXPOSE 8080
