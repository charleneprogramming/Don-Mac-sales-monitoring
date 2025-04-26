import { useState } from "react";

interface ApiError {
  message?: string;
  details?: Record<string, string[]>; 
}

interface PostResponse<T> {
  data: T | null;
  error: ApiError | null;
  loading: boolean;
  postData: (newData?: any) => Promise<void>;
}

export default function usePostData<T>(
  url: string,
  initialData?: any
): PostResponse<T> {
  const [data, setData] = useState<T | null>(null);
  const [error, setError] = useState<ApiError | null>(null);
  const [loading, setLoading] = useState<boolean>(false);

  const postData = async (newData?: any) => {
    try {
      setLoading(true);
      setError(null);
      const dataToPost = newData ?? initialData;

      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(dataToPost),
      });

      if (!response.ok) {
        const serverError = await response.json();
        setError({
          message: "Failed to post data.",
          details: serverError,
        });
      } else {
        const responseData = await response.json();
        setData(responseData);
      }
    } catch (err) {
      setError({
        message: "An unknown error occurred.",
      });
    } finally {
      setLoading(false);
    }
  };

  return { data, error, loading, postData };
}
