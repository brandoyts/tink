import type { Metadata } from "next"
import { Geist, Geist_Mono } from "next/font/google"
import "./globals.css"
import { TextGeneratorStoreProvider } from "@/lib/providers/text-generator-store-provider"
import { ImageGeneratorStoreProvider } from "@/lib/providers/image-generator-store-provider"
import { Analytics } from "@vercel/analytics/next"

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
})

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
})

export const metadata: Metadata = {
  title: "tink",
  description: "Creativity at your command",
}

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode
}>) {
  return (
    <html lang="en">
      <body
        className={`${geistSans.variable} ${geistMono.variable} antialiased h-screen`}
      >
        <TextGeneratorStoreProvider>
          <ImageGeneratorStoreProvider>{children}</ImageGeneratorStoreProvider>
        </TextGeneratorStoreProvider>
        <Analytics />
      </body>
    </html>
  )
}
