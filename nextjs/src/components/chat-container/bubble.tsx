"use client"

import { PromptEntry } from "@/lib/stores/generator-store"
import { PromptType } from "@/types/enums"
import { IconCopy, IconCopyCheck, IconDownload } from "@tabler/icons-react"
import Image from "next/image"
import { memo, useState } from "react"
import { v4 as uuidv4 } from "uuid"
import { Button } from "../ui/button"

interface BubbleProps {
  prompt: PromptEntry
}

const bubbleStyles: Record<
  PromptType,
  { alignment: string; bg?: string; text: string; maxWidth: string }
> = {
  [PromptType.UserText]: {
    alignment: "self-end",
    bg: "bg-white/20",
    text: "text-white",
    maxWidth: "max-w-[80%]",
  },
  [PromptType.AiText]: {
    alignment: "self-start",
    text: "text-white",
    maxWidth: "max-w-full",
  },
  [PromptType.AiImage]: {
    alignment: "self-end",
    text: "text-white",
    maxWidth: "max-w-[400px]",
  },
}

export const Bubble = memo(function Bubble({ prompt }: BubbleProps) {
  const key = uuidv4()
  const style = bubbleStyles[prompt.type]
  const [copied, setCopied] = useState(false)

  if (!style) {
    console.warn(`Unknown prompt type: ${prompt.type}`)
    return null
  }

  const handleDownloadClick = (url: string) => {
    window.open(url, "_blank", "noopener,noreferrer")
  }

  const handleCopyClick = async (text: string) => {
    try {
      await navigator.clipboard.writeText(text)
      setCopied(true)
      setTimeout(() => setCopied(false), 1500)
    } catch (err) {
      console.error("Failed to copy text:", err)
    }
  }

  // AI Text with copy button
  if (prompt.type === PromptType.AiText) {
    return (
      <div
        key={key}
        className={`self-start ${style.maxWidth} flex flex-col gap-1 break-words`}
      >
        <p className={style.text}>{prompt.content}</p>
        <Button
          type="button"
          size="icon"
          onClick={() => handleCopyClick(prompt.content)}
          className="self-start px-2 py-1 rounded-xl bg-transparent border border-white/20 text-white
                     text-sm hover:bg-white/20 hover:scale-105 active:scale-95 transition-all duration-200 shadow-md cursor-pointer"
        >
          {copied ? <IconCopyCheck /> : <IconCopy />}
        </Button>
      </div>
    )
  }

  // User text or AI Image bubble
  return (
    <div
      key={key}
      className={`${style.alignment} ${
        style.maxWidth
      } rounded-2xl p-4 shadow-lg border border-white/20 ${
        style.bg ?? ""
      } backdrop-blur-xl flex flex-col gap-2`}
    >
      {prompt.type === PromptType.AiImage ? (
        <>
          <Image
            src={prompt.content}
            alt="Generated AI content"
            width={1024}
            height={512}
            className="w-full h-auto object-cover rounded-2xl"
            priority
          />
          <Button
            type="button"
            size="icon"
            onClick={() => handleDownloadClick(prompt.content)}
            className="self-end px-2 py-1 rounded-xl bg-transparent border border-white/20 text-white 
                 text-sm hover:bg-white/20 hover:scale-105 active:scale-95 transition-all duration-200 shadow-md cursor-pointer"
          >
            <IconDownload />
          </Button>
        </>
      ) : (
        <p className={style.text}>{prompt.content}</p>
      )}
    </div>
  )
})
