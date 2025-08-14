import { PromptType } from "@/types/enums"
import { createStore } from "zustand/vanilla"

export type PromptEntry = {
  type: PromptType
  content: string
}

export type GeneratorState = {
  loading: boolean
  promptInput: string
  prompts: PromptEntry[]
}

export type GeneratorActions = {
  setLoading: (loading: boolean) => void
  setPromptInput: (prompt: string) => void
  appendPrompt: (prompt: PromptEntry) => void
}

export type GeneratorStore = GeneratorState & GeneratorActions

export const defaultGeneratorState: GeneratorState = {
  loading: false,
  promptInput: "",
  prompts: [],
}

export const createGeneratorStore = (
  initState: GeneratorState = defaultGeneratorState
) => {
  return createStore<GeneratorStore>()((set) => ({
    ...initState,
    setLoading: (loading) => set((state) => ({ ...state, loading })),
    setPromptInput: (promptInput) =>
      set((state) => ({ ...state, promptInput })),
    appendPrompt: (prompt) =>
      set((state) => ({ prompts: [...state.prompts, prompt] })),
  }))
}
