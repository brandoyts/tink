"use client"
import { motion, Transition } from "motion/react"
import React from "react"

export default function Loader({ text }: { text: string }) {
  const animation = {
    initial: { scale: 1, opacity: 0.4 },
    animate: {
      scale: [1, 1.05, 1],
      textShadow: [
        "0 0 0 var(--shadow-color)",
        "0 0 4px var(--shadow-color)",
        "0 0 0 var(--shadow-color)",
      ],
      opacity: [0.4, 1, 0.4],
    },
  }

  const transition = (i: number): Transition => ({
    duration: 0.8,
    repeat: Infinity,
    repeatType: "loop",
    delay: i * 0.06,
    ease: "easeInOut",
    repeatDelay: 1.5,
  })

  return (
    <div className="font-sans font-medium tracking-wide text-neutral-800 dark:text-neutral-100 [--shadow-color:rgba(59,130,246,0.6)]">
      {text.split("").map((char, i) => (
        <motion.span
          key={i}
          className="inline-block"
          initial={animation.initial}
          animate={animation.animate}
          transition={transition(i)}
        >
          {char === " " ? "\u00A0" : char}
        </motion.span>
      ))}
    </div>
  )
}
