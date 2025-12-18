<template>
  <div class="tour-schedule-manager">
    <div class="header mb-6">
      <h2 class="text-2xl font-bold text-gray-800 mb-2">Quản lý lịch trình tour</h2>
      <p class="text-gray-600">{{ tour?.name }}</p>
    </div>

    <!-- Tabs -->
    <div class="tabs mb-6">
      <nav class="flex space-x-8 border-b border-gray-200">
        <button 
          @click="activeTab = 'schedules'"
          :class="['py-2 px-1 border-b-2 font-medium text-sm', 
                   activeTab === 'schedules' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700']"
        >
          Lịch trình từng ngày
        </button>
        <button 
          @click="activeTab = 'departures'"
          :class="['py-2 px-1 border-b-2 font-medium text-sm',
                   activeTab === 'departures' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700']"
        >
          Thông tin khởi hành
        </button>
      </nav>
    </div>

    <!-- Schedule Management Tab -->
    <div v-if="activeTab === 'schedules'" class="schedules-tab">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Lịch trình từng ngày</h3>
        <button 
          @click="openScheduleModal()"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
        >
          <i class="fas fa-plus mr-2"></i>Thêm ngày mới
        </button>
      </div>

      <div class="schedules-list space-y-4">
        <div 
          v-for="schedule in schedules" 
          :key="schedule.id"
          class="schedule-card bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500"
        >
          <div class="flex justify-between items-start mb-4">
            <div>
              <h4 class="text-lg font-semibold text-gray-800">
                Ngày {{ schedule.day_number }}: {{ schedule.title }}
              </h4>
              <p class="text-gray-600 mt-1">{{ schedule.location }}</p>
              <p v-if="schedule.start_time || schedule.end_time" class="text-sm text-gray-500 mt-1">
                <i class="fas fa-clock mr-1"></i>
                {{ schedule.start_time }} - {{ schedule.end_time }}
              </p>
            </div>
            <div class="flex space-x-2">
              <button 
                @click="openScheduleModal(schedule)"
                class="text-blue-600 hover:text-blue-800 p-2"
                title="Chỉnh sửa"
              >
                <i class="fas fa-edit"></i>
              </button>
              <button 
                @click="deleteSchedule(schedule.id)"
                class="text-red-600 hover:text-red-800 p-2"
                title="Xóa"
              >
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div v-if="schedule.activities">
              <strong>Hoạt động:</strong> {{ schedule.activities }}
            </div>
            <div v-if="schedule.meals">
              <strong>Bữa ăn:</strong> {{ schedule.meals }}
            </div>
            <div v-if="schedule.accommodation">
              <strong>Nơi nghỉ:</strong> {{ schedule.accommodation }}
            </div>
            <div v-if="schedule.transportation">
              <strong>Phương tiện:</strong> {{ schedule.transportation }}
            </div>
          </div>

          <div v-if="schedule.description" class="mt-4 p-3 bg-gray-50 rounded">
            <strong>Mô tả:</strong> {{ schedule.description }}
          </div>
        </div>
      </div>
    </div>

    <!-- Departure Management Tab -->
    <div v-if="activeTab === 'departures'" class="departures-tab">
      <h3 class="text-lg font-semibold mb-4">Quản lý thông tin khởi hành</h3>
      
      <div class="departures-list space-y-4">
        <div 
          v-for="departure in departures" 
          :key="departure.id"
          class="departure-card bg-white rounded-lg shadow-md p-6"
        >
          <div class="flex justify-between items-start mb-4">
            <div>
              <h4 class="text-lg font-semibold text-gray-800">
                {{ formatDate(departure.departure_date) }}
              </h4>
              <p class="text-gray-600">{{ departure.departure_time }} - {{ departure.departure_location }}</p>
              <span :class="['inline-block px-2 py-1 rounded text-xs font-medium mt-2',
                           getStatusClass(departure.preparation_status)]">
                {{ getStatusText(departure.preparation_status) }}
              </span>
            </div>
            <button 
              @click="openDepartureModal(departure)"
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors"
            >
              <i class="fas fa-edit mr-2"></i>Chỉnh sửa
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-if="departure.guide">
              <h5 class="font-medium text-gray-700 mb-2">Hướng dẫn viên chính</h5>
              <p class="text-sm">{{ departure.guide.full_name }} ({{ departure.guide.code }})</p>
              <p class="text-sm text-gray-600">{{ departure.guide.phone }}</p>
            </div>
            <div v-if="departure.backup_guide">
              <h5 class="font-medium text-gray-700 mb-2">HDV dự phòng</h5>
              <p class="text-sm">{{ departure.backup_guide.full_name }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Schedule Modal -->
    <div v-if="showScheduleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold">
            {{ editingSchedule ? 'Chỉnh sửa lịch trình' : 'Thêm lịch trình mới' }}
          </h3>
          <button @click="closeScheduleModal" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <form @submit.prevent="saveSchedule" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Ngày thứ</label>
              <input 
                v-model="scheduleForm.day_number" 
                type="number" 
                min="1"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Địa điểm</label>
              <input 
                v-model="scheduleForm.location" 
                type="text"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề</label>
            <input 
              v-model="scheduleForm.title" 
              type="text"
              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            >
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Giờ bắt đầu</label>
              <input 
                v-model="scheduleForm.start_time" 
                type="time"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Giờ kết thúc</label>
              <input 
                v-model="scheduleForm.end_time" 
                type="time"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Điểm tập trung</label>
            <input 
              v-model="scheduleForm.meeting_point" 
              type="text"
              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hoạt động</label>
            <textarea 
              v-model="scheduleForm.activities" 
              rows="3"
              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            ></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Bữa ăn</label>
              <textarea 
                v-model="scheduleForm.meals" 
                rows="2"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nơi nghỉ</label>
              <textarea 
                v-model="scheduleForm.accommodation" 
                rows="2"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phương tiện di chuyển</label>
            <input 
              v-model="scheduleForm.transportation" 
              type="text"
              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
            <textarea 
              v-model="scheduleForm.description" 
              rows="4"
              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            ></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
            <textarea 
              v-model="scheduleForm.notes" 
              rows="3"
              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            ></textarea>
          </div>

          <div class="flex justify-end space-x-4 pt-4">
            <button 
              type="button" 
              @click="closeScheduleModal"
              class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
            >
              Hủy
            </button>
            <button 
              type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
              :disabled="saving"
            >
              {{ saving ? 'Đang lưu...' : 'Lưu' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Departure Modal -->
    <div v-if="showDepartureModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold">Chỉnh sửa thông tin khởi hành</h3>
          <button @click="closeDepartureModal" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <form @submit.prevent="saveDeparture" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Giờ khởi hành</label>
              <input 
                v-model="departureForm.departure_time" 
                type="time"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái chuẩn bị</label>
              <select 
                v-model="departureForm.preparation_status"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="pending">Chờ chuẩn bị</option>
                <option value="preparing">Đang chuẩn bị</option>
                <option value="ready">Sẵn sàng</option>
                <option value="departed">Đã khởi hành</option>
                <option value="completed">Hoàn thành</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Địa điểm khởi hành</label>
            <input 
              v-model="departureForm.departure_location" 
              type="text"
              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Hướng dẫn viên chính</label>
              <select 
                v-model="departureForm.guide_id"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="">Chọn hướng dẫn viên</option>
                <option v-for="guide in availableGuides" :key="guide.id" :value="guide.id">
                  {{ guide.full_name }} ({{ guide.code }})
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">HDV dự phòng</label>
              <select 
                v-model="departureForm.backup_guide_id"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="">Chọn HDV dự phòng</option>
                <option v-for="guide in availableGuides" :key="guide.id" :value="guide.id">
                  {{ guide.full_name }} ({{ guide.code }})
                </option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hướng dẫn khởi hành</label>
            <textarea 
              v-model="departureForm.departure_instructions" 
              rows="3"
              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            ></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Liên hệ khẩn cấp</label>
              <input 
                v-model="departureForm.emergency_contact" 
                type="text"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">SĐT khẩn cấp</label>
              <input 
                v-model="departureForm.emergency_phone" 
                type="text"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú đặc biệt</label>
            <textarea 
              v-model="departureForm.special_notes" 
              rows="3"
              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            ></textarea>
          </div>

          <div class="flex justify-end space-x-4 pt-4">
            <button 
              type="button" 
              @click="closeDepartureModal"
              class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
            >
              Hủy
            </button>
            <button 
              type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
              :disabled="saving"
            >
              {{ saving ? 'Đang lưu...' : 'Lưu' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TourScheduleManager',
  props: {
    tourId: {
      type: [String, Number],
      required: true
    }
  },
  data() {
    return {
      activeTab: 'schedules',
      tour: null,
      schedules: [],
      departures: [],
      availableGuides: [],
      
      // Modal states
      showScheduleModal: false,
      showDepartureModal: false,
      editingSchedule: null,
      editingDeparture: null,
      saving: false,
      
      // Forms
      scheduleForm: this.getEmptyScheduleForm(),
      departureForm: this.getEmptyDepartureForm()
    }
  },
  mounted() {
    this.fetchData()
    this.fetchAvailableGuides()
  },
  methods: {
    async fetchData() {
      try {
        const response = await fetch(`/api/tours/${this.tourId}/schedules`)
        const data = await response.json()
        
        if (data.success) {
          this.tour = data.data.tour
          this.schedules = data.data.schedules
          
          // Fetch departures
          const departuresResponse = await fetch(`/api/tours/${this.tourId}/departures`)
          if (departuresResponse.ok) {
            const departuresData = await departuresResponse.json()
            this.departures = departuresData.data || []
          }
        }
      } catch (error) {
        console.error('Lỗi khi tải dữ liệu:', error)
      }
    },
    
    async fetchAvailableGuides() {
      try {
        const response = await fetch('/api/guides/available')
        const data = await response.json()
        
        if (data.success) {
          this.availableGuides = data.data
        }
      } catch (error) {
        console.error('Lỗi khi tải danh sách HDV:', error)
      }
    },

    // Schedule methods
    openScheduleModal(schedule = null) {
      this.editingSchedule = schedule
      if (schedule) {
        this.scheduleForm = { ...schedule }
      } else {
        this.scheduleForm = this.getEmptyScheduleForm()
      }
      this.showScheduleModal = true
    },

    closeScheduleModal() {
      this.showScheduleModal = false
      this.editingSchedule = null
      this.scheduleForm = this.getEmptyScheduleForm()
    },

    async saveSchedule() {
      this.saving = true
      try {
        const url = this.editingSchedule 
          ? `/api/tours/${this.tourId}/schedules/${this.editingSchedule.id}`
          : `/api/tours/${this.tourId}/schedules`
        
        const method = this.editingSchedule ? 'PUT' : 'POST'
        
        const response = await fetch(url, {
          method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify(this.scheduleForm)
        })
        
        const data = await response.json()
        
        if (data.success) {
          this.closeScheduleModal()
          this.fetchData()
          alert('Lưu lịch trình thành công!')
        } else {
          alert('Lỗi: ' + data.message)
        }
      } catch (error) {
        console.error('Lỗi khi lưu lịch trình:', error)
        alert('Có lỗi xảy ra khi lưu lịch trình')
      } finally {
        this.saving = false
      }
    },

    async deleteSchedule(scheduleId) {
      if (!confirm('Bạn có chắc chắn muốn xóa lịch trình này?')) return
      
      try {
        const response = await fetch(`/api/tours/${this.tourId}/schedules/${scheduleId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        })
        
        const data = await response.json()
        
        if (data.success) {
          this.fetchData()
          alert('Xóa lịch trình thành công!')
        } else {
          alert('Lỗi: ' + data.message)
        }
      } catch (error) {
        console.error('Lỗi khi xóa lịch trình:', error)
        alert('Có lỗi xảy ra khi xóa lịch trình')
      }
    },

    // Departure methods
    openDepartureModal(departure) {
      this.editingDeparture = departure
      this.departureForm = { ...departure }
      this.showDepartureModal = true
    },

    closeDepartureModal() {
      this.showDepartureModal = false
      this.editingDeparture = null
      this.departureForm = this.getEmptyDepartureForm()
    },

    async saveDeparture() {
      this.saving = true
      try {
        const response = await fetch(`/api/departures/${this.editingDeparture.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify(this.departureForm)
        })
        
        const data = await response.json()
        
        if (data.success) {
          this.closeDepartureModal()
          this.fetchData()
          alert('Cập nhật thông tin khởi hành thành công!')
        } else {
          alert('Lỗi: ' + data.message)
        }
      } catch (error) {
        console.error('Lỗi khi lưu thông tin khởi hành:', error)
        alert('Có lỗi xảy ra khi lưu thông tin khởi hành')
      } finally {
        this.saving = false
      }
    },

    // Helper methods
    getEmptyScheduleForm() {
      return {
        day_number: 1,
        title: '',
        description: '',
        location: '',
        start_time: '',
        end_time: '',
        meeting_point: '',
        activities: '',
        meals: '',
        accommodation: '',
        transportation: '',
        notes: '',
        images: []
      }
    },

    getEmptyDepartureForm() {
      return {
        departure_time: '',
        departure_location: '',
        departure_instructions: '',
        guide_id: '',
        backup_guide_id: '',
        emergency_contact: '',
        emergency_phone: '',
        special_notes: '',
        preparation_status: 'pending'
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

    getStatusClass(status) {
      const classes = {
        pending: 'bg-gray-100 text-gray-800',
        preparing: 'bg-yellow-100 text-yellow-800',
        ready: 'bg-green-100 text-green-800',
        departed: 'bg-blue-100 text-blue-800',
        completed: 'bg-purple-100 text-purple-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    },

    getStatusText(status) {
      const texts = {
        pending: 'Chờ chuẩn bị',
        preparing: 'Đang chuẩn bị',
        ready: 'Sẵn sàng',
        departed: 'Đã khởi hành',
        completed: 'Hoàn thành'
      }
      return texts[status] || 'Không xác định'
    }
  }
}
</script>

<style scoped>
.tour-schedule-manager {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.schedule-card {
  transition: transform 0.2s ease-in-out;
}

.schedule-card:hover {
  transform: translateY(-2px);
}

.departure-card {
  transition: transform 0.2s ease-in-out;
}

.departure-card:hover {
  transform: translateY(-2px);
}

@media (max-width: 768px) {
  .tour-schedule-manager {
    padding: 10px;
  }
}
</style>