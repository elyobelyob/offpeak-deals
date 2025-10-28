-- Sample data insert for businesses in Surbiton and Kingston-upon-Thames
INSERT INTO businesses (id, name) VALUES
  (1, 'Fox & Hounds'),
  (2, 'Kingston Pizza Place');

-- Locations: addresses and city
INSERT INTO locations (id, business_id, name, address, city, region, country, postcode)
VALUES
  (1, 1, 'Fox & Hounds Surbiton', '123 High Street', 'Surbiton', 'London', 'UK', 'KT6 4AW'),
  (2, 2, 'Kingston Pizza Place', '45 Market Street', 'Kingston upon Thames', 'London', 'UK', 'KT1 1JT');

-- Deals: (id, location_id, title, description, start_time, end_time, day_of_week)
INSERT INTO deals (id, location_id, title, description, start_time, end_time, day_of_week)
VALUES
  (1, 1, 'Becks Burger Night', 'Enjoy Becks burger with fries at a special price', '18:00:00', '22:00:00', 'Thursday'),
  (2, 2, '2-for-1 Pizza Monday', 'Get two pizzas for the price of one every Monday afternoon', '12:00:00', '15:00:00', 'Monday');
