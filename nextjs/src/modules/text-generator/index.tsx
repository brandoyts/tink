"use client"

import { ChatContainer } from "@/components/chat-container"
import { PromptType } from "@/types/enums"
import React from "react"
import { Textarea } from "@/components/ui/textarea"
import { useTextGeneratorStore } from "@/lib/providers/text-generator-store-provider"

export default function TextGenerator() {
  const {
    loading,
    promptInput,
    prompts,
    setLoading,
    appendPrompt,
    setPromptInput,
  } = useTextGeneratorStore((state) => state)

  const handleFormSubmit = async (e?: React.FormEvent) => {
    if (e) {
      e.preventDefault()
    }

    const trimmed = promptInput.trim()

    if (!trimmed) {
      return
    }

    appendPrompt({
      type: PromptType.UserText,
      content: trimmed,
    })
    setPromptInput("")
    setLoading(true)

    try {
      const response = await fetch(
        `${process.env.NEXT_PUBLIC_API_BASE_URL}/generate-text`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ prompt: promptInput }),
        }
      )

      if (!response.ok) {
        throw new Error("HTTP error!")
      }

      const body = await response.json()

      appendPrompt({
        type: PromptType.AiText,
        content: body.text,
      })
    } catch (e) {
      console.error("error submitting prompt", e)
    }

    setLoading(false)
  }

  const handleChange = (e: React.ChangeEvent<HTMLTextAreaElement>) => {
    setPromptInput(e.target.value)
  }

  const onKeyDown = async (e: React.KeyboardEvent<HTMLTextAreaElement>) => {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault()
      await handleFormSubmit()
    }
  }

  return (
    <>
      <ChatContainer prompts={prompts} loading={loading} />
      <form onSubmit={handleFormSubmit} className="mt-5">
        <Textarea
          className="w-full p-4 mt-3 rounded-2xl border border-white/20 bg-white/10 backdrop-blur-xl shadow-lg
                 text-white placeholder-white/10 focus:outline-none focus:ring-2 focus:ring-blue-400/60
                 transition-all duration-300 ease-in-out"
          placeholder="what are thinking you right now?"
          value={promptInput}
          onChange={handleChange}
          onKeyDown={onKeyDown}
        />
      </form>
    </>
  )
}
