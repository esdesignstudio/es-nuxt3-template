import type { FetchOptions } from 'ohmyfetch'
// 教學 https://github.com/nuxt/framework/discussions/3828
export const esFetch = (path: string, opts?: FetchOptions) => {
	const config = useRuntimeConfig()
	return $fetch(path, { baseURL: config.public.apiBase, ...(opts && { ...opts }) })
}