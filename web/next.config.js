/** @type {import('next').NextConfig} */
const nextConfig = {
    images: {
      remotePatterns: [
        {
          protocol: 'http',
          hostname: 'localhost',
          port: '8000', // Specify the port if applicable
          pathname: '/images/**', // Match the path for your images
        },
      ],
    },
  };
  
  module.exports = nextConfig;