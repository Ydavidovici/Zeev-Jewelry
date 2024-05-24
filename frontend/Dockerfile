# frontend/Dockerfile
FROM node:14

# Set working directory
WORKDIR /usr/src/app

# Install dependencies
COPY package*.json ./
RUN npm install

# Copy project files
COPY . .

# Build the app
RUN npm run build

# Expose port
EXPOSE 3500

# Start the app
CMD ["npm", "start"]
