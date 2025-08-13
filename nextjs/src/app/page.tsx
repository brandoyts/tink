"use client"

import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"

import { AuroraBackground } from "@/components/ui/aurora-background"
import { ServiceType } from "@/types/enums"
import TextGenerator from "@/modules/text-generator"
import { motion } from "motion/react"

export default function Home() {
  return (
    <AuroraBackground>
      <motion.div
        initial={{ opacity: 0, y: 40 }}
        whileInView={{ opacity: 1, y: 0 }}
        transition={{
          delay: 0.3,
          duration: 0.8,
          ease: "easeInOut",
        }}
        className="relative flex flex-col gap-6 md:gap-10 items-center justify-center px-4 sm:px-6 lg:px-8 py-10 w-full max-w-5xl mx-auto"
      >
        {/* Title */}
        <h1 className="text-4xl sm:text-5xl md:text-7xl font-bold text-white drop-shadow-lg tracking-wide text-center">
          tink
        </h1>

        {/* Subtitle */}
        <p className="font-light text-base sm:text-lg md:text-2xl text-white/90 text-center max-w-2xl leading-relaxed">
          Creativity at your command
        </p>

        {/* Tabs */}
        <Tabs defaultValue={ServiceType.Text} className="w-full">
          <TabsList className="mb-6 flex flex-wrap justify-center gap-3 bg-white/10 backdrop-blur-lg border border-white/20 rounded-full p-2">
            <TabsTrigger
              value={ServiceType.Text}
              className="transition-all duration-200 data-[state=active]:bg-white/25 data-[state=active]:backdrop-blur-md rounded-full px-6 py-2 text-sm sm:text-base hover:bg-white/10"
            >
              Text Generator
            </TabsTrigger>
            <TabsTrigger
              value={ServiceType.Image}
              className="transition-all duration-200 data-[state=active]:bg-white/25 data-[state=active]:backdrop-blur-md rounded-full px-6 py-2 text-sm sm:text-base hover:bg-white/10"
            >
              Image Generator
            </TabsTrigger>
          </TabsList>

          <TabsContent value={ServiceType.Text} className="animate-fadeIn">
            <TextGenerator />
          </TabsContent>

          <TabsContent value="password" className="text-white/80 text-center">
            Change your password here.
          </TabsContent>
        </Tabs>
      </motion.div>
    </AuroraBackground>
  )
}
