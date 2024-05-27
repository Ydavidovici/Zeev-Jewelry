# Use an official Node runtime as a parent image
FROM node:14

# Set working directory
WORKDIR /usr/src/app

# Install ca-certificates and update the SSL certificates
RUN apt-get update && apt-get install -y ca-certificates && update-ca-certificates

# Copy the CA certificate file
COPY cacert.pem /usr/local/share/ca-certificates/cacert.crt

# Update CA certificates
RUN update-ca-certificates

# Copy npm configuration if it exists
COPY .npmrc /root/.npmrc

# Ensure .npmrc is properly configured
RUN echo "cafile=/usr/local/share/ca-certificates/cacert.crt" >> /root/.npmrc

# Copy package.json and package-lock.json
COPY package*.json ./

# Debug step: Verify certificate file and npm config
RUN cat /usr/local/share/ca-certificates/cacert.crt
RUN npm config get cafile

# Install dependencies
RUN npm install

# Copy the rest of the application code
COPY . .

# Expose port and start application
EXPOSE 3000
CMD ["npm", "run", "dev"]
