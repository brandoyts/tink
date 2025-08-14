"use client"

import { useLayoutEffect, useRef } from "react"
import { Card, CardContent, CardFooter } from "@/components/ui/card"
import Loader from "../ui/loader"
import { PromptEntry } from "@/lib/stores/generator-store"
import { Bubble } from "./bubble"
import { v4 as uuidv4 } from "uuid"

interface ChatContainerProps {
  prompts: PromptEntry[]
  loading: boolean
}

export function ChatContainer({ prompts, loading }: ChatContainerProps) {
  const containerRef = useRef<HTMLDivElement>(null)

  useLayoutEffect(() => {
    containerRef.current?.scrollTo({
      top: containerRef.current.scrollHeight,
      behavior: "smooth",
    })
  }, [prompts, loading])

  return (
    <Card
      ref={containerRef}
      className="w-full h-[600px] overflow-y-auto rounded-2xl border border-white/20 bg-white/5 backdrop-blur-lg shadow-lg
                 scrollbar-thumb-rounded-full scrollbar-track-rounded-full scrollbar
                 scrollbar-thumb-white/20 scrollbar-track-transparent
                 transition-all duration-300 ease-in-out"
    >
      <CardContent className="flex flex-col gap-6">
        {prompts.map((p) => (
          <Bubble key={uuidv4() || p.content} prompt={p} />
        ))}
      </CardContent>
      <CardFooter className="flex self-center">
        {loading && <Loader text="Generating..." />}
      </CardFooter>
    </Card>
  )
}
