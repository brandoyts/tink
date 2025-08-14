"use client"

import { type ReactNode, createContext, useRef, useContext } from "react"
import { useStore } from "zustand"

import {
  type GeneratorStore,
  createGeneratorStore,
} from "@/lib/stores/generator-store"

export type ImageGeneratorStoreApi = ReturnType<typeof createGeneratorStore>

export const ImageGeneratorStoreContext = createContext<
  ImageGeneratorStoreApi | undefined
>(undefined)

export interface ImageGeneratorStoreProviderProps {
  children: ReactNode
}
export const ImageGeneratorStoreProvider = ({
  children,
}: ImageGeneratorStoreProviderProps) => {
  const storeRef = useRef<ImageGeneratorStoreApi | null>(null)
  if (storeRef.current === null) {
    storeRef.current = createGeneratorStore()
  }

  return (
    <ImageGeneratorStoreContext.Provider value={storeRef.current}>
      {children}
    </ImageGeneratorStoreContext.Provider>
  )
}

export const useImageGeneratorStore = <T,>(
  selector: (store: GeneratorStore) => T
): T => {
  const imageGeneratorStoreContext = useContext(ImageGeneratorStoreContext)

  if (!imageGeneratorStoreContext) {
    throw new Error(
      `useTextGeneratorStore must be used within CounterStoreProvider`
    )
  }

  return useStore(imageGeneratorStoreContext, selector)
}
