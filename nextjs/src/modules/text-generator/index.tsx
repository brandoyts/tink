"use client"

import React, { useState } from "react"

import { Textarea } from "@/components/ui/textarea"
import { ChatContainer } from "@/components/chat-container"

export default function TextGenerator() {
  const [prompt, setPrompt] = useState("")
  const [prompts, setPrompts] = useState<string[]>([])
  const [loading, setLoading] = useState(false)

  const handleFormSubmit = async (e?: React.FormEvent) => {
    if (e) {
      e.preventDefault()
    }

    if (!prompt.trim()) {
      return
    }

    setPrompts((prev) => [...prev, prompt])
    setPrompt("")
    setLoading(true)

    try {
      const response = await fetch(
        "http://localhost:8000/api/v1/generate-text",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ prompt }),
        }
      )

      if (!response.ok) {
        throw new Error("HTTP error!")
      }

      const body = await response.json()

      setPrompts((prev) => [...prev, body.text])
    } catch (e) {
      console.error("error submitting prompt", e)
    }

    setLoading(false)
  }

  const handleChange = (e: React.ChangeEvent<HTMLTextAreaElement>) => {
    setPrompt(e.target.value)
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
          // className="bg-white"
          className="w-full p-4 mt-3 rounded-2xl border border-white/20 bg-white/10 backdrop-blur-xl shadow-lg
                 text-white placeholder-white/10 focus:outline-none focus:ring-2 focus:ring-blue-400/60
                 transition-all duration-300 ease-in-out"
          placeholder="what are thinking you right now?"
          value={prompt}
          onChange={handleChange}
          onKeyDown={onKeyDown}
        />
      </form>
    </>
  )
}
