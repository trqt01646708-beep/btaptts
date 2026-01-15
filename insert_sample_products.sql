-- Clear existing data (disable foreign key checks)
SET FOREIGN_KEY_CHECKS = 0;

DELETE FROM posts;
DELETE FROM category_post;
DELETE FROM products;
DELETE FROM categories;

-- Reset auto-increment
ALTER TABLE categories AUTO_INCREMENT = 1;
ALTER TABLE products AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS = 1;

-- Insert Sample Categories
INSERT INTO categories (name, slug, description, status, created_at, updated_at) VALUES 
('Điện tử', 'dien-tu', 'Các sản phẩm điện tử', 'active', NOW(), NOW()),
('Thời trang', 'thoi-trang', 'Quần áo và phụ kiện thời trang', 'active', NOW(), NOW()),
('Gia dụng', 'gia-dung', 'Đồ dùng gia đình', 'active', NOW(), NOW()),
('Thể thao', 'the-thao', 'Dụng cụ thể thao', 'active', NOW(), NOW()),
('Sách', 'sach', 'Sách và tài liệu', 'active', NOW(), NOW());

-- Insert Sample Products (Electronics)
INSERT INTO products (category_id, name, slug, image, description, content, regular_price, sale_price, stock_quantity, status, published_at, created_at, updated_at) VALUES 
(1, 'Laptop Dell XPS 15', 'laptop-dell-xps-15', 'https://via.placeholder.com/800x600?text=Laptop+Dell+XPS', 'Laptop hiệu suất cao với màn hình đẹp', '<p>Laptop Dell XPS 15 với bộ xử lý Intel Core i9, RAM 32GB, SSD 1TB. Màn hình 4K OLED cực đẹp.</p>', 25000000, 22000000, 10, 'active', NOW(), NOW(), NOW()),
(1, 'iPhone 15 Pro Max', 'iphone-15-pro-max', 'https://via.placeholder.com/800x600?text=iPhone+15+Pro', 'Điện thoại thông minh cao cấp', '<p>iPhone 15 Pro Max với chip A17 Pro, camera 48MP, pin 4685mAh</p>', 32000000, 29000000, 15, 'active', NOW(), NOW(), NOW()),
(1, 'iPad Air 6', 'ipad-air-6', 'https://via.placeholder.com/800x600?text=iPad+Air', 'Máy tính bảng mạnh mẽ', '<p>iPad Air 6 với chip M2, màn hình 11 inch, 256GB storage</p>', 18000000, 16500000, 12, 'active', NOW(), NOW(), NOW()),
(1, 'Samsung 65" QLED TV', 'samsung-65-qled-tv', 'https://via.placeholder.com/800x600?text=Samsung+QLED', 'Tivi QLED 65 inch 4K', '<p>Tivi Samsung QLED 65 inch 4K 120Hz, smart TV</p>', 45000000, 40000000, 5, 'active', NOW(), NOW(), NOW()),
(1, 'Sony WH-1000XM5 Headphones', 'sony-headphones', 'https://via.placeholder.com/800x600?text=Sony+Headphones', 'Tai nghe chống ồn tốt nhất', '<p>Tai nghe wireless Sony với ANC tốt nhất thế giới, pin 30 giờ</p>', 8000000, 7200000, 20, 'active', NOW(), NOW(), NOW()),

-- Insert Sample Products (Fashion)
(2, 'Nike Air Max 2024', 'nike-air-max-2024', 'https://via.placeholder.com/800x600?text=Nike+Air+Max', 'Giày chạy bộ chuyên dụng', '<p>Nike Air Max 2024 với công nghệ Airmax mới, rất thoải mái</p>', 3500000, 2800000, 30, 'active', NOW(), NOW(), NOW()),
(2, 'Áo thun Gucci Nam', 'ao-thun-gucci-nam', 'https://via.placeholder.com/800x600?text=Gucci+Tshirt', 'Áo thun hàng hiệu', '<p>Áo thun Gucci nam màu đen, chất cotton 100%, size M-XXL</p>', 5000000, 4200000, 25, 'active', NOW(), NOW(), NOW()),
(2, 'Quần Jeans Levi\'s 501', 'quan-jeans-levis', 'https://via.placeholder.com/800x600?text=Levis+Jeans', 'Quần jeans cổ điển', '<p>Quần jeans Levi\'s 501 xanh đen, kinh điển và bền</p>', 2000000, 1500000, 40, 'active', NOW(), NOW(), NOW()),
(2, 'Túi Chanel Classic Flap', 'tui-chanel-classic', 'https://via.placeholder.com/800x600?text=Chanel+Bag', 'Túi xách hàng hiệu', '<p>Túi Chanel classic flap đen, da cao cấp, khóa vàng</p>', 45000000, 42000000, 8, 'active', NOW(), NOW(), NOW()),
(2, 'Đồng hồ Rolex Submariner', 'dong-ho-rolex', 'https://via.placeholder.com/800x600?text=Rolex+Watch', 'Đồng hồ sang trọng', '<p>Đồng hồ Rolex Submariner, thép không gỉ, chống nước 300m</p>', 500000000, 480000000, 3, 'active', NOW(), NOW(), NOW()),

-- Insert Sample Products (Home & Garden)
(3, 'Tủ lạnh Samsung Inverter', 'tu-lanh-samsung', 'https://via.placeholder.com/800x600?text=Samsung+Fridge', 'Tủ lạnh tiết kiệm điện', '<p>Tủ lạnh Samsung Inverter 550L, 2 dàn lạnh, ngăn đá mềm</p>', 20000000, 17500000, 12, 'active', NOW(), NOW(), NOW()),
(3, 'Máy rửa bát Bosch', 'may-rua-bat-bosch', 'https://via.placeholder.com/800x600?text=Bosch+Dishwasher', 'Máy rửa bát hiệu quả', '<p>Máy rửa bát Bosch 14 bộ, tiết kiệm nước, chế độ nước nóng</p>', 15000000, 13500000, 10, 'active', NOW(), NOW(), NOW()),
(3, 'Lò nướng Panasonic', 'lo-nuong-panasonic', 'https://via.placeholder.com/800x600?text=Panasonic+Oven', 'Lò nướng thông minh', '<p>Lò nướng Panasonic 30L, tự động mở cửa, 30 chương trình nấu</p>', 8500000, 7200000, 15, 'active', NOW(), NOW(), NOW()),
(3, 'Giường ngủ gỗ tự nhiên', 'giuong-ngu-go', 'https://via.placeholder.com/800x600?text=Wooden+Bed', 'Giường ngủ cao cấp', '<p>Giường ngủ gỗ sồi tự nhiên, kích thước 1.6m x 2m, 1 nệm</p>', 12000000, 10500000, 8, 'active', NOW(), NOW(), NOW()),
(3, 'Ghế sofa da thật', 'ghe-sofa-da', 'https://via.placeholder.com/800x600?text=Leather+Sofa', 'Sofa da thật cao cấp', '<p>Ghế sofa da thật 3 chỗ, thiết kế hiện đại, rất thoải mái</p>', 18000000, 15500000, 6, 'active', NOW(), NOW(), NOW()),

-- Insert Sample Products (Sports)
(4, 'Vợt cầu lông Victor', 'vot-cau-long-victor', 'https://via.placeholder.com/800x600?text=Victor+Badminton', 'Vợt cầu lông chuyên dụng', '<p>Vợt cầu lông Victor 3370, nhẹ, bền, cho ngành chuyên</p>', 2000000, 1700000, 25, 'active', NOW(), NOW(), NOW()),
(4, 'Xe đạp địa hình Giant', 'xe-dap-dia-hinh', 'https://via.placeholder.com/800x600?text=Giant+Bike', 'Xe đạp địa hình cao cấp', '<p>Xe đạp Giant XTC 29er, khung nhôm, 21 tốc độ, lốp địa hình</p>', 8500000, 7200000, 10, 'active', NOW(), NOW(), NOW()),
(4, 'Bộ tạ yoga 6kg', 'bo-ta-yoga', 'https://via.placeholder.com/800x600?text=Yoga+Weights', 'Tạ yoga chuyên dụng', '<p>Bộ tạ yoga 6kg, tay cầm thoải mái, chất liệu composite</p>', 1200000, 999000, 40, 'active', NOW(), NOW(), NOW()),
(4, 'Máy chạy bộ Decathlon', 'may-chay-bo-decathlon', 'https://via.placeholder.com/800x600?text=Treadmill', 'Máy chạy bộ gia đình', '<p>Máy chạy bộ Decathlon, 12 chế độ, màn hình LCD, gấp gọn</p>', 6500000, 5500000, 8, 'active', NOW(), NOW(), NOW()),
(4, 'Dụng cụ tập gym combo', 'dung-cu-gym-combo', 'https://via.placeholder.com/800x600?text=Gym+Equipment', 'Bộ dụng cụ tập gym', '<p>Bộ dụng cụ gym 15 chi tiết, tạ, xà, dây kéo, bao cát</p>', 4500000, 3800000, 12, 'active', NOW(), NOW(), NOW()),

-- Insert Sample Products (Books)
(5, 'Lập trình Python cho người mới', 'lap-trinh-python', 'https://via.placeholder.com/800x600?text=Python+Book', 'Sách hướng dẫn lập trình', '<p>Sách dạy lập trình Python từ cơ bản đến nâng cao, 400 trang</p>', 350000, 280000, 50, 'active', NOW(), NOW(), NOW()),
(5, 'Nghệ thuật bán hàng', 'nghe-thuat-ban-hang', 'https://via.placeholder.com/800x600?text=Sales+Book', 'Sách kỹ năng bán hàng', '<p>Sách hướng dẫn kỹ năng bán hàng hiệu quả, 280 trang</p>', 250000, 200000, 35, 'active', NOW(), NOW(), NOW()),
(5, 'Tư duy khởi nghiệp', 'tu-duy-khoi-nghiep', 'https://via.placeholder.com/800x600?text=Startup+Book', 'Sách về khởi nghiệp', '<p>Sách hướng dẫn tư duy khởi nghiệp, kinh doanh online, 320 trang</p>', 280000, 220000, 45, 'active', NOW(), NOW(), NOW()),
(5, 'Tâm lý học hành vi', 'tam-ly-hoc-hanh-vi', 'https://via.placeholder.com/800x600?text=Psychology+Book', 'Sách tâm lý học', '<p>Sách về tâm lý học hành vi, hiểu người khác tốt hơn, 360 trang</p>', 320000, 260000, 40, 'active', NOW(), NOW(), NOW()),
(5, 'Kỹ năng quản lý thời gian', 'ky-nang-quan-ly-thoi-gian', 'https://via.placeholder.com/800x600?text=Time+Management', 'Sách quản lý thời gian', '<p>Sách dạy quản lý thời gian hiệu quả, tăng năng suất, 240 trang</p>', 200000, 160000, 60, 'active', NOW(), NOW(), NOW());

-- Insert Sample Posts
INSERT INTO posts (title, slug, content, status, published_at, created_at, updated_at) VALUES
('Bí quyết chọn laptop phù hợp năm 2026', 'bi-quyet-chon-laptop-2026', '<h2>Chọn laptop phù hợp có thể khó</h2><p>Trong bài viết này, chúng tôi sẽ chia sẻ những bí quyết để lựa chọn chiếc laptop phù hợp nhất với nhu cầu của bạn.</p><h3>Những yếu tố cần xem xét:</h3><ul><li>Mục đích sử dụng (công việc, chơi game, đồ họa)</li><li>Ngân sách của bạn</li><li>Cấu hình phần cứng cần thiết</li><li>Tuổi thọ pin</li><li>Trọng lượng và kích thước</li></ul><p>Hãy đánh giá kỹ lưỡng trước khi mua để không hối tiếc!</p>', 'published', NOW(), NOW(), NOW()),

('Xu hướng thời trang 2026 không nên bỏ qua', 'xu-huong-thoi-trang-2026', '<h2>Những xu hướng thời trang hot nhất 2026</h2><p>Năm 2026 mang đến những thay đổi đáng kể trong ngành thời trang. Dưới đây là những xu hướng bạn không nên bỏ qua:</p><h3>1. Phong cách tối giản</h3><p>Phong cách minimalist tiếp tục chiếm ưu thế với những trang phục đơn giản nhưng sang trọng.</p><h3>2. Màu sắc tự nhiên</h3><p>Các màu sắc tự nhiên như be, xám, trắng vẫn được ưa chuộng.</p><h3>3. Chất liệu bền vững</h3><p>Ngành thời trang đang chuyển sang sử dụng các chất liệu thân thiện với môi trường.</p>', 'published', NOW(), NOW(), NOW()),

('Cách chọn ghế sofa hoàn hảo cho phòng khách', 'chon-ghe-sofa-phong-khach', '<h2>Ghế sofa - Điểm nhấn của phòng khách</h2><p>Ghế sofa không chỉ là nơi để ngồi mà còn là yếu tố trang trí quan trọng trong phòng khách. Hãy xem cách chọn ghế sofa hoàn hảo:</p><h3>Kích thước phù hợp</h3><p>Đo đạc kích thước phòng khách của bạn trước khi mua sofa. Sofa không nên quá lớn hay quá nhỏ.</p><h3>Chọn màu sắc khôn ngoan</h3><p>Chọn màu sắc hòa hợp với tông màu chung của phòng khách. Màu trung tính dễ kết hợp hơn.</p><h3>Chất liệu quan trọng</h3><p>Chất liệu da thật bền hơn vải, nhưng vải thoáng hơn và dễ vệ sinh hơn.</p>', 'published', NOW(), NOW(), NOW()),

('Hướng dẫn tập gym hiệu quả cho người mới bắt đầu', 'huong-dan-tap-gym-nguoi-moi', '<h2>Bắt đầu hành trình fitness của bạn</h2><p>Tập gym lần đầu có thể gây lo lắng, nhưng đừng lo! Dưới đây là hướng dẫn chi tiết cho người mới:</p><h3>Khởi động đúng cách</h3><p>Luôn bắt đầu với 5-10 phút khởi động nhẹ để chuẩn bị cơ bắp.</p><h3>Bắt đầu với trọng lượng nhỏ</h3><p>Không nên tập quá nặng ngay từ đầu. Hãy tăng dần theo tuần.</p><h3>Kế hoạch tập luyện</h3><p>Hãy xây dựng một kế hoạch tập luyện rõ ràng với mục tiêu cụ thể.</p><h3>Dinh dưỡng và nghỉ ngơi</h3><p>Không quên ăn uống đủ chất dinh dưỡng và nghỉ ngơi đủ 8 tiếng mỗi ngày.</p>', 'published', NOW(), NOW(), NOW()),

('Sách lập trình Python - Cuốn sách bạn cần đọc', 'sach-lap-trinh-python-can-doc', '<h2>Tại sao nên học Python?</h2><p>Python là một trong những ngôn ngữ lập trình dễ học nhất và được sử dụng rộng rãi trong ngành công nghệ:</p><h3>Cú pháp đơn giản</h3><p>Python có cú pháp rõ ràng và dễ hiểu, hoàn hảo cho người mới bắt đầu.</p><h3>Ứng dụng rộng rãi</h3><p>Python được sử dụng trong khoa học dữ liệu, AI, web development, và nhiều lĩnh vực khác.</p><h3>Cộng đồng lớn</h3><p>Cộng đồng Python rất lớn với nhiều thư viện và công cụ hỗ trợ.</p><h3>Cuốn sách này sẽ dạy bạn</h3><p>Từ các khái niệm cơ bản đến các dự án thực tế, cuốn sách cung cấp tất cả những gì bạn cần biết.</p>', 'published', NOW(), NOW(), NOW()),

('Lợi ích của việc đọc sách và cách tạo thói quen đọc', 'loi-ich-doc-sach-tao-thoi-quen', '<h2>Tại sao nên đọc sách thường xuyên?</h2><p>Đọc sách không chỉ là một hoạt động giải trí mà còn mang lại nhiều lợi ích cho sức khỏe và trí tuệ:</p><h3>Phát triển kiến thức</h3><p>Mỗi cuốn sách là một cơ hội để học hỏi điều mới và mở rộng kiến thức.</p><h3>Giảm stress</h3><p>Đọc sách giúp giảm stress và thúc đẩy thư giãn.</p><h3>Cải thiện kỹ năng ghi nhớ</h3><p>Khi đọc, bộ não của bạn hoạt động để xử lý thông tin, giúp cải thiện trí nhớ.</p><h3>Tạo thói quen đọc</h3><p>Hãy bắt đầu với những cuốn sách ngắn và dễ đọc. Đặt ra mục tiêu đọc hàng tháng.</p>', 'published', NOW(), NOW(), NOW()),

('Công nghệ AI đang thay đổi cuộc sống của chúng ta như thế nào', 'ai-thay-doi-cuoc-song', '<h2>Trí tuệ nhân tạo - Tương lai đã đến</h2><p>Trí tuệ nhân tạo (AI) không còn là khái niệm tương lai mà đã hiện diện trong cuộc sống hàng ngày của chúng ta:</p><h3>Trong các thiết bị thông minh</h3><p>AI được sử dụng trong các trợ lý ảo, điện thoại thông minh, và các thiết bị IoT khác.</p><h3>Trong y tế</h3><p>AI giúp chẩn đoán bệnh, phát triển thuốc, và cải thiện chất lượng chăm sóc sức khỏe.</p><h3>Trong giao thông</h3><p>Xe tự lái được phát triển nhờ công nghệ AI tiên tiến.</p><h3>Trong giáo dục</h3><p>AI cá nhân hóa quá trình học tập, giúp mỗi học sinh theo nhịp độ của chính họ.</p><p>Tương lai sẽ có nhiều thay đổi hơn nữa với sự phát triển của AI!</p>', 'published', NOW(), NOW(), NOW()),

('Những lợi ích sức khỏe của thể thao thường xuyên', 'loi-ich-the-thao-suc-khoe', '<h2>Tập thể thao - Chìa khóa sống khỏe</h2><p>Tập thể thao thường xuyên không chỉ giúp duy trì cân nặng mà còn mang lại nhiều lợi ích khác cho sức khỏe:</p><h3>Tăng cường sức khoẻ tim mạch</h3><p>Thể dục giúp tim hoạt động hiệu quả hơn và giảm nguy cơ bệnh tim.</p><h3>Cải thiện sức mạnh cơ bắp</h3><p>Tập luyện đều đặn giúp xây dựng và duy trì sức mạnh cơ bắp.</p><h3>Cải thiện sức khỏe tâm thần</h3><p>Thể dục giúp giảm stress, lo âu và trầm cảm.</p><h3>Tăng tuổi thọ</h3><p>Những người tập thể thao thường xuyên có tuổi thọ dài hơn.</p><h3>Gợi ý tập thể thao</h3><p>Hãy tập ít nhất 150 phút mỗi tuần, kết hợp giữa tập cardio và tập sức mạnh.</p>', 'published', NOW(), NOW(), NOW()),

('Các sản phẩm điện tử phải có trong nhà năm 2026', 'san-pham-dien-tu-can-co-2026', '<h2>Những thiết bị điện tử hiện đại cho nhà thông minh</h2><p>Năm 2026 là thời điểm tốt để nâng cấp các thiết bị điện tử trong nhà. Dưới đây là những sản phẩm bạn nên cân nhắc:</p><h3>TV QLED</h3><p>Các tivi QLED cung cấp chất lượng hình ảnh xuất sắc và có tính năng smart TV.</p><h3>Tai nghe chống ồn</h3><p>Tai nghe chống ồn chủ động giúp bạn tập trung và thưởng thức âm nhạc tốt hơn.</p><h3>Laptop hiệu suất cao</h3><p>Để làm việc từ nhà hoặc làm đồ họa, bạn cần một laptop mạnh mẽ.</p><h3>Điện thoại thông minh mới</h3><p>Các smartphone mới có camera tốt hơn, pin lâu hơn, và tốc độ xử lý nhanh hơn.</p>', 'published', NOW(), NOW(), NOW());

