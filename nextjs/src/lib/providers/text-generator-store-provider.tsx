"use client"

import { type ReactNode, createContext, useRef, useContext } from "react"
import { useStore } from "zustand"

import {
  type GeneratorStore,
  createGeneratorStore,
} from "@/lib/stores/generator-store"

export type TextGeneratorStoreApi = ReturnType<typeof createGeneratorStore>

export const TextGeneratorStoreContext = createContext<
  TextGeneratorStoreApi | undefined
>(undefined)

export interface TextGeneratorStoreProviderProps {
  children: ReactNode
}
export const TextGeneratorStoreProvider = ({
  children,
}: TextGeneratorStoreProviderProps) => {
  const storeRef = useRef<TextGeneratorStoreApi | null>(null)
  if (storeRef.current === null) {
    storeRef.current = createGeneratorStore()
  }

  return (
    <TextGeneratorStoreContext.Provider value={storeRef.current}>
      {children}
    </TextGeneratorStoreContext.Provider>
  )
}

export const useTextGeneratorStore = <T,>(
  selector: (store: GeneratorStore) => T
): T => {
  const textGeneratorStoreContext = useContext(TextGeneratorStoreContext)

  if (!textGeneratorStoreContext) {
    throw new Error(
      `useTextGeneratorStore must be used within CounterStoreProvider`
    )
  }

  return useStore(textGeneratorStoreContext, selector)
}
