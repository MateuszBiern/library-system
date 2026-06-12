// API Configuration
export const API_BASE_URL = '/api'

export const API_ENDPOINTS = {
  books: `${API_BASE_URL}/books`,
  bookChapters: `${API_BASE_URL}/book_chapters`,
  chapterContent: `${API_BASE_URL}/chapter_content`,
  login: `${API_BASE_URL}/login`,
  register: `${API_BASE_URL}/register`,
  addBook: `${API_BASE_URL}/add_book`,
  deleteBook: `${API_BASE_URL}/book_manager`,
  tags: `${API_BASE_URL}/tags`,
  rateBook: `${API_BASE_URL}/rate_book`,
  getRatings: `${API_BASE_URL}/get_ratings`,
  getUserRating: `${API_BASE_URL}/get_user_rating`,
  getRecommendedBooks: `${API_BASE_URL}/get_recommended_books`,
}
