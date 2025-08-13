import { useEffect, useRef } from "react"
import { Card, CardContent, CardFooter } from "@/components/ui/card"
import { cn } from "@/lib/utils"
import Loader from "../ui/loader"

export function ChatContainer(props: { prompts: string[]; loading: boolean }) {
  const containerRef = useRef<HTMLDivElement>(null)

  useEffect(() => {
    if (containerRef.current) {
      containerRef.current.scrollTop = containerRef.current.scrollHeight
    }
  }, [props.prompts])

  const renderPrompts = () => {
    return props.prompts.map((chat, index) => (
      <p
        key={index}
        className={cn(
          "max-w-[80%] rounded-2xl p-3 text-base leading-relaxed shadow-md border border-white/20 backdrop-blur-lg transition-all duration-300",
          index % 2 === 0
            ? "bg-white/20 text-gray-900 self-end" // User prompt - lighter
            : "bg-white/10 text-gray-100 self-start" // Reply - darker glass
        )}
      >
        {chat}
      </p>
    ))
  }

  const renderLoader = () => {
    return props.loading && <Loader text={"Generating..."} />
  }

  return (
    <Card
      className="w-full h-[600px] overflow-y-auto 
             rounded-2xl border border-white/20 bg-white/10 backdrop-blur-xl shadow-lg
             scrollbar-thumb-rounded-full scrollbar-track-rounded-full scrollbar
             scrollbar-thumb-white/20 scrollbar-track-transparent
             transition-all duration-300 ease-in-out"
      ref={containerRef}
    >
      <CardContent className="flex flex-col gap-10">
        {renderPrompts()}
      </CardContent>
      <CardFooter className="flex self-center">{renderLoader()}</CardFooter>
    </Card>
  )
}
