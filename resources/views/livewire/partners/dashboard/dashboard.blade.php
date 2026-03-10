<div>
    <div>
        <div>
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <div class="stat-value">{{ $totalParcels }}</div>
                    <div class="stat-label">Total Parcels Today</div>
                    <!-- <div class="stat-change positive">
                        <i class="bi bi-arrow-up"></i>
                        <span>12% from yesterday</span>
                    </div> -->
                </div>

                <div class="stat-card success">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-value">{{ $inTransitParcels }}</div>
                    <div class="stat-label">Processed</div>
                    <!-- <div class="stat-change positive">
                        <i class="bi bi-arrow-up"></i>
                        <span>8% from yesterday</span>
                    </div> -->
                </div>

                <div class="stat-card warning">
                    <div class="stat-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stat-value">{{ $pendingParcels }}</div>
                    <div class="stat-label">Pending</div>
                    <!-- <div class="stat-change negative">
                        <i class="bi bi-arrow-down"></i>
                        <span>3 from yesterday</span>
                    </div> -->
                </div>


                <div class="stat-card info">
                    <div class="stat-icon">
                        <i class="bi bi-cash"></i>
                    </div>
                    <div class="stat-value">Ksh 45,820</div>
                    <div class="stat-label">Today's Revenue</div>
                    <!-- <div class="stat-change positive">
                        <i class="bi bi-arrow-up"></i>
                        <span>15% from yesterday</span>
                    </div> -->
                </div>
            </div>

            <!-- Quick Actions -->
            <!-- <div class="dashboard-section">
            <div class="section-header">
                <h3 class="section-title">Quick Actions</h3>
            </div>
            <div class="quick-actions">
                <a href="#" class="action-card">
                    <div class="action-icon">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <div class="action-title">New Parcel</div>
                    <p class="action-desc">Register incoming parcel</p>
                </a>

                <a href="#" class="action-card">
                    <div class="action-icon">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>
                    <div class="action-title">Scan Parcel</div>
                    <p class="action-desc">Scan QR code for tracking</p>
                </a>

                <a href="#" class="action-card">
                    <div class="action-icon">
                        <i class="bi bi-printer"></i>
                    </div>
                    <div class="action-title">Print Labels</div>
                    <p class="action-desc">Print shipping labels</p>
                </a>

                <a href="#" class="action-card">
                    <div class="action-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="action-title">Schedule Pickup</div>
                    <p class="action-desc">Schedule courier pickup</p>
                </a>
            </div>
        </div> -->

            <!-- Recent Activity & Recent Parcels -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="dashboard-section">
                        <div class="section-header">
                            <h3 class="section-title">Recent Activity</h3>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <ul class="activity-list">
                            <li class="activity-item activity-success">
                                <div class="activity-icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Parcel #KP-2024-00123 delivered</div>
                                    <div class="activity-time">2 minutes ago</div>
                                </div>
                            </li>
                            <li class="activity-item activity-warning">
                                <div class="activity-icon">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Parcel #KP-2024-00145 requires attention</div>
                                    <div class="activity-time">15 minutes ago</div>
                                </div>
                            </li>
                            <li class="activity-item activity-info">
                                <div class="activity-icon">
                                    <i class="bi bi-box"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">New parcel received from sender</div>
                                    <div class="activity-time">1 hour ago</div>
                                </div>
                            </li>
                            <li class="activity-item activity-success">
                                <div class="activity-icon">
                                    <i class="bi bi-cash"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Payment received for 5 parcels</div>
                                    <div class="activity-time">2 hours ago</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="dashboard-section">
                        <div class="section-header">
                            <h3 class="section-title">Recent Parcels</h3>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tracking ID</th>
                                        <th>Status</th>
                                        <th>Destination</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>KP-2024-00156</td>
                                        <td><span class="status-badge status-pending">Pending</span></td>
                                        <td>Mombasa</td>
                                        <td>10:30 AM</td>
                                    </tr>
                                    <tr>
                                        <td>KP-2024-00155</td>
                                        <td><span class="status-badge status-processing">Processing</span></td>
                                        <td>Kisumu</td>
                                        <td>09:45 AM</td>
                                    </tr>
                                    <tr>
                                        <td>KP-2024-00154</td>
                                        <td><span class="status-badge status-delivered">Delivered</span></td>
                                        <td>Nakuru</td>
                                        <td>Yesterday</td>
                                    </tr>
                                    <tr>
                                        <td>KP-2024-00153</td>
                                        <td><span class="status-badge status-processing">Processing</span></td>
                                        <td>Eldoret</td>
                                        <td>Yesterday</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>