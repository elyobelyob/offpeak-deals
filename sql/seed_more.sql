-- Additional seed data for offpeak-deals
-- Adds businesses, locations (with latitude/longitude) and sample deals

SET FOREIGN_KEY_CHECKS=0;

-- Businesses
INSERT INTO businesses (id, name) VALUES
 (101, 'The Corner Deli'),
 (102, 'Seaside Sushi'),
 (103, 'Pasta & Co'),
 (104, 'Taco Republic'),
 (105, 'Veggie Delight');

-- Locations (business_id, address_line1, address_line2, city, state, postal_code, latitude, longitude)
INSERT INTO locations (id, business_id, address_line1, address_line2, city, state, postal_code, latitude, longitude) VALUES
 (201, 101, '12 Baker St', '', 'London', 'London', 'NW1 6XE', 51.515419, -0.156098),
 (202, 102, '200 Ocean Ave', '', 'Santa Monica', 'CA', '90401', 34.010287, -118.496475),
 (203, 103, 'Via Roma 10', '', 'Rome', 'RM', '00184', 41.902783, 12.496366),
 (204, 104, '5th Ave', 'Unit 2', 'New York', 'NY', '10001', 40.712776, -74.005974),
 (205, 105, '1 Veg St', '', 'Melbourne', 'VIC', '3000', -37.813629, 144.963058),
 (206, 101, '88 Market Rd', '', 'London', 'London', 'SW1A 1AA', 51.501476, -0.140634);

-- Deals (id, title, description, day_of_week, start_time, end_time, location_id)
INSERT INTO deals (id, title, description, day_of_week, start_time, end_time, location_id) VALUES
 (301, 'Two-for-One Sandwiches', 'Buy one sandwich, get the second free between 14:00 and 16:00', 2, '14:00:00', '16:00:00', 201),
 (302, 'Sushi Happy Hour', 'Half-price rolls during late afternoon', 3, '15:00:00', '17:00:00', 202),
 (303, 'Pasta Lunch Special', 'Set pasta + drink for a fixed price', 1, '11:30:00', '14:00:00', 203),
 (304, 'Taco Tuesday', 'Discounted tacos all day', 2, '11:00:00', '22:00:00', 204),
 (305, 'Veggie Brunch', 'Weekend brunch special for vegetarian dishes', 7, '10:00:00', '14:00:00', 205),
 (306, 'Early Bird Sandwich', 'Early lunchtime discount', 1, '08:00:00', '10:30:00', 201);

SET FOREIGN_KEY_CHECKS=1;
