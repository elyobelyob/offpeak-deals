-- Migration 002: add country column to locations
-- Run this after migrations/001_initial_schema.sql and migrations/001_add_lat_long.sql

ALTER TABLE locations
  ADD COLUMN country VARCHAR(100) DEFAULT NULL;
