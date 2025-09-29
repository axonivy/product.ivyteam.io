FROM eclipse-temurin:25-jre-noble

ADD target/product-listing-service*.jar /app.jar

CMD ["java", "-jar", "/app.jar"]
EXPOSE 8080
