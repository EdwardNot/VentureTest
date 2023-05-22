FROM node:16

WORKDIR /app/
ENV PATH /app/node_modules/.bin:$PATH

COPY ./package.json ./
COPY package-lock.json ./
RUN npm install --silent
RUN npm install react-scripts@3.4.1 -g --silent
RUN npm install react-player@2.10.1 --silent
COPY . ./

# section PROD
# RUN npm run build
# RUN npm install -g serve
# endsection PROD

EXPOSE 3000

# section PROD
# CMD ["serve", "-s", "/app/build", "-l", "3000"]
# endsection PROD

# section DEV
CMD ["npm", "run", "start"]
# endsection DEV
