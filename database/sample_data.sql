-- Insert sample restaurants
INSERT INTO restaurants (user_id, name, cuisine_type, description, address, contact_number, rating, created_at) VALUES
(1, 'La Piazza', 'Italian', 'Experience authentic Italian cuisine in a warm, rustic atmosphere. Our handmade pasta and wood-fired pizzas will transport you straight to Italy.', '123 Main Street, Downtown', '+1 (555) 123-4567', 4.8, NOW()),
(1, 'Sakura Japanese Restaurant', 'Japanese', 'Traditional Japanese dining featuring fresh sushi, sashimi, and hot dishes. Our master chefs create artistic presentations that delight both the eyes and palate.', '456 Cherry Blossom Lane', '+1 (555) 234-5678', 4.7, NOW()),
(1, 'El Mariachi', 'Mexican', 'Vibrant Mexican restaurant serving family recipes passed down through generations. Known for our tableside guacamole and premium tequila selection.', '789 Fiesta Boulevard', '+1 (555) 345-6789', 4.6, NOW()),
(1, 'The Golden Dragon', 'Chinese', 'Authentic Chinese cuisine featuring dim sum and Cantonese specialties. Our chefs bring decades of experience from Hong Kong.', '321 Dragon Street', '+1 (555) 456-7890', 4.5, NOW()),
(1, 'Taj Mahal', 'Indian', 'Journey through India''s diverse culinary landscape with our aromatic curries and tandoor specialties. Extensive vegetarian options available.', '654 Spice Road', '+1 (555) 567-8901', 4.9, NOW()),
(1, 'Le Petit Bistro', 'French', 'Charming French bistro offering classic dishes and an extensive wine list. Perfect for romantic dinners and special occasions.', '987 Rue de Paris', '+1 (555) 678-9012', 4.7, NOW()),
(1, 'Mediterranean Oasis', 'Mediterranean', 'Fresh Mediterranean cuisine featuring Greek, Turkish, and Lebanese specialties. Known for our fresh seafood and mezze platters.', '147 Olive Grove Lane', '+1 (555) 789-0123', 4.6, NOW()),
(1, 'The Grill House', 'American', 'Premium steakhouse featuring aged beef and fresh seafood. Our open kitchen and wood-fired grill create an unforgettable dining experience.', '258 Steakhouse Drive', '+1 (555) 890-1234', 4.8, NOW()),
(1, 'Bangkok Spice', 'Thai', 'Authentic Thai cuisine with a modern twist. Our chefs use traditional recipes and fresh ingredients to create bold, flavorful dishes.', '369 Spice Lane', '+1 (555) 901-2345', 4.7, NOW()),
(1, 'Seoul Garden', 'Korean', 'Experience the rich flavors of Korean cuisine with our signature BBQ and traditional dishes. Our banchan (side dishes) are made fresh daily.', '741 Kimchi Street', '+1 (555) 012-3456', 4.8, NOW()),
(1, 'The Green Leaf', 'Vegetarian', 'Innovative plant-based cuisine that celebrates fresh, seasonal ingredients. Our menu changes daily to showcase the best local produce.', '852 Garden Avenue', '+1 (555) 123-7890', 4.6, NOW()),
(1, 'Ocean''s Catch', 'Seafood', 'Fresh seafood restaurant featuring daily catches and sustainable seafood options. Our raw bar and seafood towers are customer favorites.', '963 Harbor View', '+1 (555) 234-8901', 4.9, NOW()),
(1, 'Pizza Paradiso', 'Italian', 'Artisanal pizzas made with imported Italian ingredients and cooked in our wood-fired oven. Our dough is made fresh daily.', '159 Pizza Street', '+1 (555) 345-9012', 4.5, NOW()),
(1, 'The Curry House', 'Indian', 'Authentic North and South Indian cuisine with a focus on traditional cooking methods. Our tandoori dishes are cooked in a clay oven.', '357 Curry Lane', '+1 (555) 456-0123', 4.7, NOW()),
(1, 'Sushi Master', 'Japanese', 'Premium sushi and sashimi prepared by master chefs. We source our fish daily from sustainable fisheries.', '456 Sushi Avenue', '+1 (555) 567-1234', 4.8, NOW()),
(1, 'Taco Fiesta', 'Mexican', 'Modern Mexican street food with a gourmet twist. Our tacos and margaritas are made with fresh, authentic ingredients.', '258 Fiesta Road', '+1 (555) 678-2345', 4.6, NOW()),
(1, 'The Burger Joint', 'American', 'Gourmet burgers made with locally sourced beef and creative toppings. Our hand-cut fries are a customer favorite.', '369 Burger Street', '+1 (555) 789-3456', 4.5, NOW()),
(1, 'Pho Express', 'Vietnamese', 'Authentic Vietnamese pho and other traditional dishes. Our broth is simmered for 24 hours to achieve perfect flavor.', '147 Noodle Lane', '+1 (555) 890-4567', 4.7, NOW());

-- Insert sample photos
INSERT INTO restaurant_photos (restaurant_id, photo_url, is_primary) VALUES
-- La Piazza photos
((SELECT id FROM restaurants WHERE name = 'La Piazza'), 'uploads/restaurants/la_piazza_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'La Piazza'), 'uploads/restaurants/la_piazza_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'La Piazza'), 'uploads/restaurants/la_piazza_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'La Piazza'), 'uploads/restaurants/la_piazza_4.jpg', false),

-- Sakura Japanese photos
((SELECT id FROM restaurants WHERE name = 'Sakura Japanese Restaurant'), 'uploads/restaurants/sakura_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'Sakura Japanese Restaurant'), 'uploads/restaurants/sakura_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Sakura Japanese Restaurant'), 'uploads/restaurants/sakura_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Sakura Japanese Restaurant'), 'uploads/restaurants/sakura_4.jpg', false),

-- El Mariachi photos
((SELECT id FROM restaurants WHERE name = 'El Mariachi'), 'uploads/restaurants/mariachi_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'El Mariachi'), 'uploads/restaurants/mariachi_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'El Mariachi'), 'uploads/restaurants/mariachi_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'El Mariachi'), 'uploads/restaurants/mariachi_4.jpg', false),

-- The Golden Dragon photos
((SELECT id FROM restaurants WHERE name = 'The Golden Dragon'), 'uploads/restaurants/dragon_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'The Golden Dragon'), 'uploads/restaurants/dragon_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'The Golden Dragon'), 'uploads/restaurants/dragon_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'The Golden Dragon'), 'uploads/restaurants/dragon_4.jpg', false),

-- Taj Mahal photos
((SELECT id FROM restaurants WHERE name = 'Taj Mahal'), 'uploads/restaurants/taj_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'Taj Mahal'), 'uploads/restaurants/taj_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Taj Mahal'), 'uploads/restaurants/taj_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Taj Mahal'), 'uploads/restaurants/taj_4.jpg', false),

-- Le Petit Bistro photos
((SELECT id FROM restaurants WHERE name = 'Le Petit Bistro'), 'uploads/restaurants/bistro_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'Le Petit Bistro'), 'uploads/restaurants/bistro_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Le Petit Bistro'), 'uploads/restaurants/bistro_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Le Petit Bistro'), 'uploads/restaurants/bistro_4.jpg', false),

-- Mediterranean Oasis photos
((SELECT id FROM restaurants WHERE name = 'Mediterranean Oasis'), 'uploads/restaurants/med_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'Mediterranean Oasis'), 'uploads/restaurants/med_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Mediterranean Oasis'), 'uploads/restaurants/med_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Mediterranean Oasis'), 'uploads/restaurants/med_4.jpg', false),

-- The Grill House photos
((SELECT id FROM restaurants WHERE name = 'The Grill House'), 'uploads/restaurants/grill_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'The Grill House'), 'uploads/restaurants/grill_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'The Grill House'), 'uploads/restaurants/grill_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'The Grill House'), 'uploads/restaurants/grill_4.jpg', false),

-- Bangkok Spice photos
((SELECT id FROM restaurants WHERE name = 'Bangkok Spice'), 'uploads/restaurants/bangkok_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'Bangkok Spice'), 'uploads/restaurants/bangkok_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Bangkok Spice'), 'uploads/restaurants/bangkok_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Bangkok Spice'), 'uploads/restaurants/bangkok_4.jpg', false),

-- Seoul Garden photos
((SELECT id FROM restaurants WHERE name = 'Seoul Garden'), 'uploads/restaurants/seoul_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'Seoul Garden'), 'uploads/restaurants/seoul_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Seoul Garden'), 'uploads/restaurants/seoul_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Seoul Garden'), 'uploads/restaurants/seoul_4.jpg', false),

-- The Green Leaf photos
((SELECT id FROM restaurants WHERE name = 'The Green Leaf'), 'uploads/restaurants/green_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'The Green Leaf'), 'uploads/restaurants/green_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'The Green Leaf'), 'uploads/restaurants/green_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'The Green Leaf'), 'uploads/restaurants/green_4.jpg', false),

-- Ocean''s Catch photos
((SELECT id FROM restaurants WHERE name = 'Ocean''s Catch'), 'uploads/restaurants/ocean_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'Ocean''s Catch'), 'uploads/restaurants/ocean_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Ocean''s Catch'), 'uploads/restaurants/ocean_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Ocean''s Catch'), 'uploads/restaurants/ocean_4.jpg', false),

-- Pizza Paradiso photos
((SELECT id FROM restaurants WHERE name = 'Pizza Paradiso'), 'uploads/restaurants/pizza_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'Pizza Paradiso'), 'uploads/restaurants/pizza_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Pizza Paradiso'), 'uploads/restaurants/pizza_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Pizza Paradiso'), 'uploads/restaurants/pizza_4.jpg', false),

-- The Curry House photos
((SELECT id FROM restaurants WHERE name = 'The Curry House'), 'uploads/restaurants/curry_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'The Curry House'), 'uploads/restaurants/curry_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'The Curry House'), 'uploads/restaurants/curry_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'The Curry House'), 'uploads/restaurants/curry_4.jpg', false),

-- Sushi Master photos
((SELECT id FROM restaurants WHERE name = 'Sushi Master'), 'uploads/restaurants/sushi_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'Sushi Master'), 'uploads/restaurants/sushi_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Sushi Master'), 'uploads/restaurants/sushi_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Sushi Master'), 'uploads/restaurants/sushi_4.jpg', false),

-- Taco Fiesta photos
((SELECT id FROM restaurants WHERE name = 'Taco Fiesta'), 'uploads/restaurants/taco_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'Taco Fiesta'), 'uploads/restaurants/taco_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Taco Fiesta'), 'uploads/restaurants/taco_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Taco Fiesta'), 'uploads/restaurants/taco_4.jpg', false),

-- The Burger Joint photos
((SELECT id FROM restaurants WHERE name = 'The Burger Joint'), 'uploads/restaurants/burger_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'The Burger Joint'), 'uploads/restaurants/burger_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'The Burger Joint'), 'uploads/restaurants/burger_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'The Burger Joint'), 'uploads/restaurants/burger_4.jpg', false),

-- Pho Express photos
((SELECT id FROM restaurants WHERE name = 'Pho Express'), 'uploads/restaurants/pho_1.jpg', true),
((SELECT id FROM restaurants WHERE name = 'Pho Express'), 'uploads/restaurants/pho_2.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Pho Express'), 'uploads/restaurants/pho_3.jpg', false),
((SELECT id FROM restaurants WHERE name = 'Pho Express'), 'uploads/restaurants/pho_4.jpg', false); 