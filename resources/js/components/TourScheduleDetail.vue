<template>
  <div class="tour-schedule-detail">
    <!-- Header thông tin tour -->
    <div class="schedule-header bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6 rounded-lg mb-6">
      <h2 class="text-2xl font-bold mb-2">{{ tour?.name }}</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
        <div>
          <i class="fas fa-calendar-alt mr-2"></i>
          <span>{{ formatDate(departure?.departure_date) }}</span>
        </div>
        <div>
          <i class="fas fa-clock mr-2"></i>
          <span>{{ departure?.departure_time }}</span>
        </div>
        <div>
          <i class="fas fa-map-marker-alt mr-2"></i>
          <span>{{ departure?.departure_location }}</span>
        </div>
      </div>
    </div>

    <!-- Thông tin hướng dẫn viên -->
    <div v-if="departure?.guide" class="guide-info bg-white rounded-lg shadow-md p-6 mb-6">
      <h3 class="text-lg font-semibold mb-4 text-gray-800">
        <i class="fas fa-user-tie mr-2 text-blue-600"></i>
        Thông tin hướng dẫn viên
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="guide-primary">
          <h4 class="font-medium text-gray-700 mb-2">Hướng dẫn viên chính</h4>
          <div class="bg-blue-50 p-4 rounded-lg">
            <p class="font-semibold text-blue-800">{{ departure.guide.name }}</p>
            <p class="text-sm text-gray-600">
              <i class="fas fa-phone mr-1"></i>{{ departure.guide.phone }}
            </p>
            <p class="text-sm text-gray-600">
              <i class="fas fa-envelope mr-1"></i>{{ departure.guide.email }}
            </p>

          </div>
        </div>
        
        <div v-if="departure.backup_guide" class="guide-backup">
          <h4 class="font-medium text-gray-700 mb-2">Hướng dẫn viên dự phòng</h4>
          <div class="bg-gray-50 p-4 rounded-lg">
            <p class="font-semibold text-gray-800">{{ departure.backup_guide.name }}</p>
            <p class="text-sm text-gray-600">
              <i class="fas fa-phone mr-1"></i>{{ departure.backup_guide.phone }}
            </p>
          </div>
        </div>
      </div>

      <!-- Thông tin khẩn cấp -->
      <div v-if="departure.emergency_contact" class="emergency-info mt-4 p-4 bg-red-50 rounded-lg border-l-4 border-red-400">
        <h4 class="font-medium text-red-800 mb-2">
          <i class="fas fa-exclamation-triangle mr-2"></i>
          Liên hệ khẩn cấp
        </h4>
        <p class="text-sm text-red-700">{{ departure.emergency_contact }}: {{ departure.emergency_phone }}</p>
      </div>
    </div>

    <!-- Hướng dẫn khởi hành -->
    <div v-if="departure?.departure_instructions" class="departure-info bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
      <h3 class="text-lg font-semibold mb-2 text-yellow-800">
        <i class="fas fa-info-circle mr-2"></i>
        Hướng dẫn khởi hành
      </h3>
      <p class="text-yellow-700">{{ departure.departure_instructions }}</p>
    </div>

    <!-- Lịch trình từng ngày -->
    <div class="schedule-timeline">
      <h3 class="text-xl font-bold mb-6 text-gray-800">
        <i class="fas fa-route mr-2 text-blue-600"></i>
        Lịch trình chi tiết
      </h3>
      
      <div class="space-y-6">
        <div 
          v-for="(schedule, index) in schedules" 
          :key="schedule.id"
          class="schedule-day bg-white rounded-lg shadow-md overflow-hidden"
        >
          <!-- Day header -->
          <div class="day-header bg-gradient-to-r from-green-500 to-green-600 text-white p-4">
            <div class="flex items-center justify-between">
              <h4 class="text-lg font-semibold">
                Ngày {{ schedule.day_number }}: {{ schedule.title }}
              </h4>
              <div v-if="schedule.start_time || schedule.end_time" class="text-sm">
                <i class="fas fa-clock mr-1"></i>
                {{ schedule.start_time }} - {{ schedule.end_time }}
              </div>
            </div>
          </div>

          <!-- Day content -->
          <div class="day-content p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Left column -->
              <div class="space-y-4">
                <!-- Location -->
                <div v-if="schedule.location" class="info-item">
                  <h5 class="font-semibold text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                    Địa điểm
                  </h5>
                  <p class="text-gray-600">{{ schedule.location }}</p>
                </div>

                <!-- Meeting point -->
                <div v-if="schedule.meeting_point" class="info-item">
                  <h5 class="font-semibold text-gray-700 mb-2">
                    <i class="fas fa-users mr-2 text-blue-500"></i>
                    Điểm tập trung
                  </h5>
                  <p class="text-gray-600">{{ schedule.meeting_point }}</p>
                </div>

                <!-- Activities -->
                <div v-if="schedule.activities" class="info-item">
                  <h5 class="font-semibold text-gray-700 mb-2">
                    <i class="fas fa-hiking mr-2 text-green-500"></i>
                    Hoạt động
                  </h5>
                  <p class="text-gray-600">{{ schedule.activities }}</p>
                </div>

                <!-- Transportation -->
                <div v-if="schedule.transportation" class="info-item">
                  <h5 class="font-semibold text-gray-700 mb-2">
                    <i class="fas fa-bus mr-2 text-purple-500"></i>
                    Phương tiện
                  </h5>
                  <p class="text-gray-600">{{ schedule.transportation }}</p>
                </div>
              </div>

              <!-- Right column -->
              <div class="space-y-4">
                <!-- Meals -->
                <div v-if="schedule.meals" class="info-item">
                  <h5 class="font-semibold text-gray-700 mb-2">
                    <i class="fas fa-utensils mr-2 text-orange-500"></i>
                    Bữa ăn
                  </h5>
                  <p class="text-gray-600">{{ schedule.meals }}</p>
                </div>

                <!-- Accommodation -->
                <div v-if="schedule.accommodation" class="info-item">
                  <h5 class="font-semibold text-gray-700 mb-2">
                    <i class="fas fa-bed mr-2 text-indigo-500"></i>
                    Nơi nghỉ
                  </h5>
                  <p class="text-gray-600">{{ schedule.accommodation }}</p>
                </div>

                <!-- Notes -->
                <div v-if="schedule.notes" class="info-item">
                  <h5 class="font-semibold text-gray-700 mb-2">
                    <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>
                    Ghi chú
                  </h5>
                  <p class="text-gray-600">{{ schedule.notes }}</p>
                </div>
              </div>
            </div>

            <!-- Description -->
            <div v-if="schedule.description" class="description mt-6 p-4 bg-gray-50 rounded-lg">
              <h5 class="font-semibold text-gray-700 mb-2">Mô tả chi tiết</h5>
              <p class="text-gray-600">{{ schedule.description }}</p>
            </div>

            <!-- Images -->
            <div v-if="schedule.images && schedule.images.length > 0" class="images mt-6">
              <h5 class="font-semibold text-gray-700 mb-3">Hình ảnh</h5>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <img 
                  v-for="(image, imgIndex) in schedule.images" 
                  :key="imgIndex"
                  :src="image" 
                  :alt="`Hình ảnh ngày ${schedule.day_number}`"
                  class="w-full h-32 object-cover rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                  @click="openImageModal(image)"
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Special notes -->
    <div v-if="departure?.special_notes" class="special-notes mt-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
      <h3 class="text-lg font-semibold mb-2 text-blue-800">
        <i class="fas fa-clipboard-list mr-2"></i>
        Ghi chú đặc biệt
      </h3>
      <p class="text-blue-700">{{ departure.special_notes }}</p>
    </div>

    <!-- Image Modal -->
    <div v-if="selectedImage" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" @click="closeImageModal">
      <div class="max-w-4xl max-h-full p-4">
        <img :src="selectedImage" alt="Hình ảnh phóng to" class="max-w-full max-h-full object-contain">
        <button @click="closeImageModal" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TourScheduleDetail',
  props: {
    tourId: {
      type: [String, Number],
      required: true
    },
    departureId: {
      type: [String, Number],
      default: null
    }
  },
  data() {
    return {
      tour: null,
      schedules: [],
      departure: null,
      loading: false,
      selectedImage: null
    }
  },
  mounted() {
    this.fetchScheduleDetails()
  },
  methods: {
    async fetchScheduleDetails() {
      this.loading = true
      try {
        const params = this.departureId ? `?departure_id=${this.departureId}` : ''
        const response = await fetch(`/api/tours/${this.tourId}/schedules${params}`)
        const data = await response.json()
        
        if (data.success) {
          this.tour = data.data.tour
          this.schedules = data.data.schedules
          this.departure = data.data.departure
        } else {
          console.error('Lỗi khi tải lịch trình:', data.message)
        }
      } catch (error) {
        console.error('Lỗi khi tải lịch trình:', error)
      } finally {
        this.loading = false
      }
    },
    formatDate(dateString) {
      if (!dateString) return ''
      const date = new Date(dateString)
      return date.toLocaleDateString('vi-VN', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })
    },
    openImageModal(image) {
      this.selectedImage = image
    },
    closeImageModal() {
      this.selectedImage = null
    }
  }
}
</script>

<style scoped>
.tour-schedule-detail {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.info-item {
  border-left: 3px solid #e5e7eb;
  padding-left: 12px;
}

.schedule-day {
  transition: transform 0.2s ease-in-out;
}

.schedule-day:hover {
  transform: translateY(-2px);
}

.day-header {
  position: relative;
}

.day-header::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0.1) 100%);
}

@media (max-width: 768px) {
  .tour-schedule-detail {
    padding: 10px;
  }
  
  .grid {
    grid-template-columns: 1fr;
  }
}
</style>