FROM node:latest
RUN mkdir -p /usr/src/qstd3
WORKDIR /usr/src/qstd3
COPY package.json /usr/src/qstd3/
RUN npm install
COPY . /usr/src/qstd3
COPY .env.prod /usr/src/qstd3/.env
EXPOSE 3003
CMD ["npm", "start"]