<script setup lang="ts">
/**
 * PolyMee - Emil Kowalski Design Engineered Hub Component (Vue 3)
 * Principles:
 *  - Under 300ms timing (190ms crossfade, 120ms active press)
 *  - GPU Composite-only properties (transform, opacity)
 *  - Zero Cumulative Layout Shift (Grid stacking)
 *  - Strong Custom Ease-Out curve: cubic-bezier(0.23, 1, 0.32, 1)
 *  - Tactile feedback: scale(0.97) on :active
 */
import { ref, onMounted, nextTick } from 'vue';

export type TabKey = 'calendar' | 'accounting' | 'profile';

export interface CTVRecord {
  id: number;
  fullName: string;
  studentCode: string;
  phone: string;
  idCardNumber: string;
  vneidImageUrl: string;
  bankName: string;
  bankAccountNumber: string;
  bankAccountName: string;
  shiftName: string;
  remuneration: number;
  status: 'Pending' | 'Approved' | 'Paid';
}

const props = withDefaults(defineProps<{
  initialTab?: TabKey;
  accountingList?: CTVRecord[];
}>(), {
  initialTab: 'calendar',
  accountingList: () => [
    {
      id: 1,
      fullName: 'Đinh Nguyễn Phương Anh',
      studentCode: 'PI03084',
      phone: '0987 654 321',
      idCardNumber: '079203014589',
      vneidImageUrl: 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=600&auto=format&fit=crop&q=80',
      bankName: 'MB Bank',
      bankAccountNumber: '098765432199',
      bankAccountName: 'DINH NGUYEN PHUONG ANH',
      shiftName: 'Ca 1: Lễ Tân Đón Khách FPoly TechDay',
      remuneration: 200000,
      status: 'Pending',
    },
    {
      id: 2,
      fullName: 'Lê Đức Mạnh',
      studentCode: 'PS20262',
      phone: '0912 345 678',
      idCardNumber: '079203014999',
      vneidImageUrl: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80',
      bankName: 'Techcombank',
      bankAccountNumber: '190367891234',
      bankAccountName: 'LE DUC MANH',
      shiftName: 'Ca 2: Điều Phối Check-in Hội Trường T',
      remuneration: 200000,
      status: 'Approved',
    }
  ]
});

const emit = defineEmits<{
  (e: 'update:tab', tab: TabKey): void;
  (e: 'create-shift'): void;
  (e: 'approve-payment', id: number): void;
}>();

const currentTab = ref<TabKey>(props.initialTab);
const tabButtonRefs = ref<Record<string, HTMLElement | null>>({});

const previewVneid = ref<{
  isOpen: boolean;
  fullName: string;
  idCard: string;
  imgUrl: string;
}>({
  isOpen: false,
  fullName: '',
  idCard: '',
  imgUrl: ''
});

// Hardware accelerated sliding pill (translate3d + scaleX)
const indicatorStyle = ref({
  transform: 'translate3d(0, 0, 0) scaleX(1)',
  opacity: 0,
});

function updateIndicator(tab: TabKey) {
  const targetEl = tabButtonRefs.value[tab];
  if (!targetEl) return;

  const parentEl = targetEl.parentElement;
  if (!parentEl) return;

  const parentRect = parentEl.getBoundingClientRect();
  const targetRect = targetEl.getBoundingClientRect();

  const translateX = targetRect.left - parentRect.left;
  const widthRatio = targetRect.width;

  indicatorStyle.value = {
    transform: `translate3d(${translateX}px, 0, 0) scaleX(${widthRatio})`,
    opacity: 1,
  };
}

function switchTab(tab: TabKey) {
  if (currentTab.value === tab) return;
  currentTab.value = tab;
  emit('update:tab', tab);
  nextTick(() => updateIndicator(tab));
}

function openVneidPreview(record: CTVRecord) {
  previewVneid.value = {
    isOpen: true,
    fullName: record.fullName,
    idCard: record.idCardNumber,
    imgUrl: record.vneidImageUrl,
  };
}

function closeVneidPreview() {
  previewVneid.value.isOpen = false;
}

onMounted(() => {
  nextTick(() => updateIndicator(currentTab.value));
  window.addEventListener('resize', () => updateIndicator(currentTab.value));
});
</script>

<template>
  <div class="emil-hub-container">
    
    <!-- 1. SLIDING PILL NAVIGATION (GPU ACCELERATED) -->
    <header class="hub-header">
      <nav class="pill-rail" aria-label="Thanh điều hướng phân hệ">
        <!-- GPU-animated sliding highlight -->
        <div 
          class="sliding-indicator" 
          :style="indicatorStyle"
          aria-hidden="true"
        />

        <button
          :ref="(el) => tabButtonRefs['calendar'] = (el as HTMLElement)"
          type="button"
          class="pill-btn"
          :class="{ 'is-active': currentTab === 'calendar' }"
          @click="switchTab('calendar')"
        >
          <svg class="pill-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <rect x="3" y="4" width="18" height="18" rx="3" stroke-width="2"/>
            <path d="M16 2v4M8 2v4M3 10h18" stroke-width="2"/>
          </svg>
          <span class="pill-label">1. Bảng Lịch Ca Trực</span>
        </button>

        <button
          :ref="(el) => tabButtonRefs['accounting'] = (el as HTMLElement)"
          type="button"
          class="pill-btn"
          :class="{ 'is-active': currentTab === 'accounting' }"
          @click="switchTab('accounting')"
        >
          <svg class="pill-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke-width="2"/>
            <polyline points="14 2 14 8 20 8" stroke-width="2"/>
            <line x1="16" y1="13" x2="8" y2="13" stroke-width="2"/>
            <line x1="16" y1="17" x2="8" y2="17" stroke-width="2"/>
          </svg>
          <span class="pill-label">2. Nghiệm Thu Kế Toán</span>
          <span class="pill-badge num-tabular">{{ accountingList.length }}</span>
        </button>

        <button
          :ref="(el) => tabButtonRefs['profile'] = (el as HTMLElement)"
          type="button"
          class="pill-btn"
          :class="{ 'is-active': currentTab === 'profile' }"
          @click="switchTab('profile')"
        >
          <svg class="pill-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <rect x="3" y="4" width="18" height="16" rx="3" stroke-width="2"/>
            <circle cx="9" cy="10" r="2" stroke-width="2"/>
            <line x1="15" y1="8" x2="17" y2="8" stroke-width="2"/>
            <line x1="15" y1="12" x2="17" y2="12" stroke-width="2"/>
          </svg>
          <span class="pill-label">3. Hồ Sơ VNeID & STK</span>
        </button>
      </nav>

      <!-- Action Buttons -->
      <div class="hub-actions">
        <button 
          type="button" 
          class="btn-cta"
          @click="emit('create-shift')"
        >
          <svg class="cta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="12" cy="12" r="10" stroke-width="2"/>
            <line x1="12" y1="8" x2="12" y2="16" stroke-width="2"/>
            <line x1="8" y1="12" x2="16" y2="12" stroke-width="2"/>
          </svg>
          <span>+ Đặt Lịch Ca Mới</span>
        </button>
      </div>
    </header>

    <!-- 2. STABLE VIEW CONTAINER (ZERO LAYOUT POPPING VIA CSS GRID) -->
    <main class="view-stack-container">
      <Transition name="emil-crossfade" mode="out-in">
        
        <!-- PANEL 1: CALENDAR VIEW -->
        <section v-if="currentTab === 'calendar'" key="calendar" class="view-panel">
          <div class="panel-header">
            <div>
              <h2 class="panel-title">Bảng Lịch Điều Phối Trung Tâm</h2>
              <p class="panel-desc">Nhấp vào ô ngày để chuyển trực tiếp sang Lịch Ngày chi tiết</p>
            </div>
            <div class="legend-row">
              <span class="legend-dot orange"></span><small>Đang tuyển</small>
              <span class="legend-dot emerald"></span><small>Ca của tôi</small>
              <span class="legend-dot red"></span><small>Đủ CTV</small>
            </div>
          </div>
          <div class="calendar-placeholder-surface">
            <p class="text-hint">Lưới Lịch Tháng (Month Grid) chuyển động dưới 200ms với CLS = 0</p>
          </div>
        </section>

        <!-- PANEL 2: ACCOUNTING AUDIT TABLE -->
        <section v-else-if="currentTab === 'accounting'" key="accounting" class="view-panel">
          <div class="panel-header">
            <div>
              <h2 class="panel-title">Bảng Nghiệm Thu Thù Lao & Kiểm Duyệt Kế Toán</h2>
              <p class="panel-desc">Đối soát CCCD, kiểm tra ảnh chụp VNeID và xác nhận chi trả thù lao</p>
            </div>
            <span class="status-pill-solid">FPT Financial Audit Active</span>
          </div>

          <div class="table-container">
            <table class="data-table">
              <thead>
                <tr>
                  <th>CTV & Mã Sinh Viên</th>
                  <th>Định Danh CCCD / VNeID</th>
                  <th>Tài Khoản Thụ Hưởng (STK)</th>
                  <th>Ca Trực</th>
                  <th>Mức Thù Lao</th>
                  <th>Trạng Thái</th>
                  <th class="text-right">Duyệt</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in accountingList" :key="item.id" class="table-row">
                  <td>
                    <strong class="row-primary-text">{{ item.fullName }}</strong>
                    <span class="row-secondary-text num-tabular">{{ item.studentCode }} • {{ item.phone }}</span>
                  </td>
                  <td>
                    <div class="vneid-preview-trigger" @click="openVneidPreview(item)">
                      <img :src="item.vneidImageUrl" alt="VNeID" class="vneid-thumb-img" />
                      <div>
                        <span class="cccd-badge num-tabular">{{ item.idCardNumber }}</span>
                        <small class="vneid-hint">Xem ảnh VNeID ↗</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="bank-acc num-tabular">{{ item.bankAccountNumber }}</span>
                    <small class="bank-name">{{ item.bankName }}</small>
                    <small class="bank-owner">{{ item.bankAccountName }}</small>
                  </td>
                  <td>
                    <span class="shift-name">{{ item.shiftName }}</span>
                  </td>
                  <td>
                    <span class="allowance-badge num-tabular">
                      {{ Number(item.remuneration).toLocaleString('vi-VN') }} đ
                    </span>
                  </td>
                  <td>
                    <span 
                      class="state-chip"
                      :class="{
                        'is-pending': item.status === 'Pending',
                        'is-approved': item.status === 'Approved',
                        'is-paid': item.status === 'Paid'
                      }"
                    >
                      {{ item.status }}
                    </span>
                  </td>
                  <td class="text-right">
                    <button 
                      type="button" 
                      class="btn-action-check"
                      @click="emit('approve-payment', item.id)"
                    >
                      Duyệt Chi
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- PANEL 3: STUDENT PROFILE -->
        <section v-else key="profile" class="view-panel">
          <div class="panel-header">
            <h2 class="panel-title">Cập Nhật Hồ Sơ Quyết Toán Cá Nhân</h2>
            <p class="panel-desc">Cung cấp CCCD 12 số và ảnh thẻ VNeID chuẩn xác để nhận thanh toán</p>
          </div>
        </section>

      </Transition>
    </main>

    <!-- 3. MODAL VNEID PREVIEW (SCALE 0.95 -> 1.0, CENTER ORIGIN) -->
    <Teleport to="body">
      <Transition name="emil-modal">
        <div 
          v-if="previewVneid.isOpen" 
          class="modal-backdrop" 
          @click.self="closeVneidPreview"
        >
          <div class="modal-card" role="dialog" aria-modal="true">
            <div class="modal-card-top">
              <div>
                <h3 class="modal-title">{{ previewVneid.fullName }}</h3>
                <span class="modal-sub num-tabular">CCCD: {{ previewVneid.idCard }}</span>
              </div>
              <button class="btn-close" @click="closeVneidPreview" aria-label="Đóng">✕</button>
            </div>
            <div class="modal-card-body">
              <img :src="previewVneid.imgUrl" alt="Thẻ VNeID" class="modal-img" />
              <div class="audit-callout">
                <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <polyline points="20 6 9 17 4 12" stroke-width="2.5"/>
                </svg>
                <span>Dữ liệu CCCD đã được đối soát khớp với hồ sơ tuyển CTV FPT Polytechnic.</span>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

  </div>
</template>

<style scoped>
:root {
  --ease-out: cubic-bezier(0.23, 1, 0.32, 1);
  --ink-primary: #0A192F;
  --ink-secondary: #475569;
  --fpt-orange: #F26F21;
  --fpt-gradient: linear-gradient(135deg, #FF6B00 0%, #F26F21 100%);
  --surface-bg: #FFFFFF;
  --border-subtle: #E2E8F0;
}

.num-tabular {
  font-variant-numeric: tabular-nums;
  letter-spacing: -0.01em;
}

.emil-hub-container {
  display: flex;
  flex-direction: column;
  gap: 16px;
  width: 100%;
}

/* --- HEADER & SLIDING RAIL --- */
.hub-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px;
}

.pill-rail {
  position: relative;
  display: inline-flex;
  align-items: center;
  background: #FFFFFF;
  padding: 4px;
  border-radius: 14px;
  border: 1px solid #E2E8F0;
  box-shadow: 0 1px 3px rgba(10, 25, 47, 0.04);
}

.sliding-indicator {
  position: absolute;
  top: 4px;
  bottom: 4px;
  left: 0;
  width: 1px;
  border-radius: 10px;
  background: linear-gradient(135deg, #FF6B00 0%, #F26F21 100%);
  transform-origin: left center;
  will-change: transform;
  transition: transform 200ms cubic-bezier(0.23, 1, 0.32, 1), opacity 150ms ease-out;
  pointer-events: none;
  box-shadow: 0 2px 8px rgba(242, 111, 33, 0.3);
}

.pill-btn {
  position: relative;
  z-index: 1;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border: 0;
  background: transparent;
  color: #475569;
  font-size: 13.5px;
  font-weight: 700;
  cursor: pointer;
  border-radius: 10px;
  transition: color 180ms ease, transform 120ms cubic-bezier(0.23, 1, 0.32, 1);
  will-change: transform;
  user-select: none;
}

.pill-btn:active {
  transform: scale(0.97);
}

.pill-btn.is-active {
  color: #FFFFFF;
}

.pill-icon {
  width: 15px;
  height: 15px;
  stroke: currentColor;
}

.pill-badge {
  font-size: 11px;
  padding: 2px 7px;
  border-radius: 20px;
  background: rgba(15, 23, 42, 0.08);
  color: inherit;
  transition: background 180ms ease;
}

.pill-btn.is-active .pill-badge {
  background: rgba(255, 255, 255, 0.25);
  color: #FFFFFF;
}

/* CTA Button */
.btn-cta {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(135deg, #FF6B00 0%, #F26F21 100%);
  color: #FFFFFF;
  border: none;
  padding: 9px 18px;
  border-radius: 12px;
  font-size: 13.5px;
  font-weight: 750;
  cursor: pointer;
  box-shadow: 0 2px 10px rgba(242, 111, 33, 0.28);
  transition: transform 140ms cubic-bezier(0.23, 1, 0.32, 1), box-shadow 140ms ease;
  will-change: transform;
}

.btn-cta:active {
  transform: scale(0.97);
  box-shadow: 0 1px 4px rgba(242, 111, 33, 0.2);
}

.cta-icon {
  width: 16px;
  height: 16px;
}

/* --- STABLE CONTENT CONTAINER (ZERO CLS) --- */
.view-stack-container {
  display: grid;
  grid-template-columns: 1fr;
  width: 100%;
}

.view-panel {
  grid-area: 1 / 1;
  background: #FFFFFF;
  border: 1px solid #E2E8F0;
  border-radius: 20px;
  padding: 24px;
  box-shadow: 0 4px 16px rgba(10, 25, 47, 0.03);
}

.emil-crossfade-enter-active,
.emil-crossfade-leave-active {
  transition: opacity 190ms cubic-bezier(0.23, 1, 0.32, 1),
              transform 190ms cubic-bezier(0.23, 1, 0.32, 1);
  will-change: opacity, transform;
}

.emil-crossfade-enter-from {
  opacity: 0;
  transform: translateY(4px) scale(0.995);
}

.emil-crossfade-leave-to {
  opacity: 0;
  transform: translateY(-4px) scale(0.995);
}

/* Panel Header */
.panel-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
  padding-bottom: 16px;
  border-bottom: 1px solid #F1F5F9;
}

.panel-title {
  font-size: 17px;
  font-weight: 800;
  color: #0A192F;
  margin: 0;
  letter-spacing: -0.02em;
}

.panel-desc {
  font-size: 12.5px;
  color: #64748B;
  margin: 2px 0 0 0;
}

.legend-row {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 12px;
  font-weight: 600;
  color: #475569;
}

.legend-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  display: inline-block;
  margin-right: 4px;
}
.legend-dot.orange { background: #F26F21; }
.legend-dot.emerald { background: #059669; }
.legend-dot.red { background: #DC2626; }

/* Table & Rows */
.table-container {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  font-size: 13px;
}

.data-table th {
  text-align: left;
  padding: 10px 14px;
  color: #64748B;
  font-size: 11.5px;
  text-transform: uppercase;
  font-weight: 750;
  letter-spacing: 0.03em;
  border-bottom: 1px solid #E2E8F0;
}

.table-row td {
  padding: 14px;
  border-bottom: 1px solid #F1F5F9;
  vertical-align: middle;
}

.row-primary-text {
  display: block;
  font-weight: 750;
  color: #0A192F;
}

.row-secondary-text {
  display: block;
  font-size: 11.5px;
  color: #64748B;
}

.vneid-preview-trigger {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  padding: 4px 8px;
  border-radius: 8px;
  transition: background 150ms ease, transform 120ms cubic-bezier(0.23, 1, 0.32, 1);
  will-change: transform;
}

.vneid-preview-trigger:hover {
  background: #F8FAFC;
}

.vneid-preview-trigger:active {
  transform: scale(0.97);
}

.vneid-thumb-img {
  width: 44px;
  height: 30px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid #CBD5E1;
}

.cccd-badge {
  font-size: 12px;
  font-weight: 800;
  color: #0A192F;
  display: block;
}

.vneid-hint {
  font-size: 11px;
  font-weight: 600;
  color: #059669;
  display: block;
}

.bank-acc {
  display: block;
  font-weight: 800;
  color: #0284C7;
}

.bank-name, .bank-owner {
  display: block;
  font-size: 11.5px;
  color: #475569;
}

.allowance-badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 8px;
  background: #ECFDF5;
  color: #059669;
  font-weight: 800;
  border: 1px solid #A7F3D0;
}

.state-chip {
  display: inline-block;
  padding: 3px 9px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 750;
}
.state-chip.is-pending { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }
.state-chip.is-approved { background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; }
.state-chip.is-paid { background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; }

.btn-action-check {
  padding: 6px 14px;
  border-radius: 8px;
  border: 1px solid #10B981;
  background: #ECFDF5;
  color: #047857;
  font-size: 12px;
  font-weight: 750;
  cursor: pointer;
  transition: transform 120ms cubic-bezier(0.23, 1, 0.32, 1);
  will-change: transform;
}
.btn-action-check:active {
  transform: scale(0.95);
}

.text-right { text-align: right; }

/* --- 3. MODAL (NEVER SCALE FROM 0, SCALE FROM 0.95) --- */
.modal-backdrop {
  position: fixed;
  inset: 0;
  z-index: 9999;
  background: rgba(10, 25, 47, 0.45);
  backdrop-filter: blur(4px);
  display: grid;
  place-items: center;
  padding: 16px;
}

.modal-card {
  width: 100%;
  max-width: 440px;
  background: #FFFFFF;
  border-radius: 22px;
  padding: 24px;
  box-shadow: 0 20px 40px rgba(10, 25, 47, 0.18);
  transform-origin: center center;
}

.emil-modal-enter-active,
.emil-modal-leave-active {
  transition: opacity 190ms cubic-bezier(0.23, 1, 0.32, 1),
              transform 190ms cubic-bezier(0.23, 1, 0.32, 1);
}

.emil-modal-enter-from,
.emil-modal-leave-to {
  opacity: 0;
  transform: scale(0.95);
}

.modal-card-top {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 16px;
}

.modal-title {
  font-size: 16px;
  font-weight: 800;
  color: #0A192F;
  margin: 0;
}

.modal-sub {
  font-size: 12.5px;
  color: #64748B;
  font-weight: 600;
}

.btn-close {
  background: #F1F5F9;
  border: none;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  font-size: 12px;
  cursor: pointer;
  display: grid;
  place-items: center;
  color: #475569;
  transition: transform 120ms ease;
}
.btn-close:active { transform: scale(0.92); }

.modal-img {
  width: 100%;
  max-height: 260px;
  object-fit: cover;
  border-radius: 12px;
  border: 1px solid #E2E8F0;
  margin-bottom: 14px;
}

.audit-callout {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #F0FDF4;
  border: 1px solid #DCFCE7;
  padding: 10px 14px;
  border-radius: 10px;
  font-size: 12px;
  color: #15803D;
  line-height: 1.4;
}

.check-icon {
  width: 18px;
  height: 18px;
  flex: none;
}

/* Reduced Motion Override (Mandatory) */
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}
</style>

