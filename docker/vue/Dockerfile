FROM node:20-alpine

RUN apk add --no-cache bash curl

WORKDIR /app

COPY docker/vue/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 5173

VOLUME ["/app"]

CMD ["/entrypoint.sh"]
