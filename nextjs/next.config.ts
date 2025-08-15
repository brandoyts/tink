import type { NextConfig } from "next"

const nextConfig: NextConfig = {
  /* config options here */
  images: {
    remotePatterns: [
      {
        protocol:
          (process.env.NEXT_PUBLIC_IMAGE_STORAGE_PROTOCOL as
            | "http"
            | "https") || "http",
        hostname: process.env.NEXT_PUBLIC_IMAGE_STORAGE_DOMAIN || "localhost",
        pathname: "/**",
      },
    ],
  },
}

export default nextConfig
