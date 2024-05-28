# Use a more recent Node runtime as a parent image
FROM node:18

# Set working directory
WORKDIR /usr/src/app

# Install ca-certificates and update the SSL certificates
RUN apt-get update && apt-get install -y ca-certificates && update-ca-certificates

# Copy the CA certificate file
COPY cacert.pem /usr/local/share/ca-certificates/cacert.crt

# Update CA certificates
RUN update-ca-certificates

# Set the NODE_EXTRA_CA_CERTS environment variable
ENV NODE_EXTRA_CA_CERTS=/usr/local/share/ca-certificates/cacert.crt

# Configure npm to use the CA certificate
RUN npm config set cafile /usr/local/share/ca-certificates/cacert.crt

# Copy npm configuration if it exists
COPY .npmrc /root/.npmrc

# Copy package.json and package-lock.json
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the application code
COPY . .

# Expose port and start application
EXPOSE 3000
CMD ["npm", "run", "dev"]
