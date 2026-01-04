-- ============================================
-- BIBLIOTHÈQUE EN LIGNE - SCHÉMA COMPLET
-- ============================================

DROP DATABASE IF EXISTS bibliotheque;
CREATE DATABASE bibliotheque
CHARACTER
SET utf8mb4
COLLATE utf8mb4_unicode_ci;
USE bibliotheque;

-- ============================================
-- TABLE: USERS
-- ============================================
CREATE TABLE users
(
    id INT
    UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR
    (255) NOT NULL UNIQUE,
    password VARCHAR
    (255) NOT NULL,
    first_name VARCHAR
    (100) NOT NULL,
    last_name VARCHAR
    (100) NOT NULL,
    phone VARCHAR
    (20),
    address TEXT,
    role ENUM
    ('lecteur', 'bibliothecaire', 'admin') NOT NULL DEFAULT 'lecteur',
    status ENUM
    ('active', 'inactive', 'pending') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
    UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP
    NULL,
    INDEX idx_email
    (email),
    INDEX idx_role
    (role),
    INDEX idx_status
    (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    -- ============================================
    -- TABLE: CATEGORIES
    -- ============================================
    CREATE TABLE categories
    (
        id INT
        UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR
        (100) NOT NULL,
    slug VARCHAR
        (100) NOT NULL UNIQUE,
    parent_id INT UNSIGNED NULL,
    description TEXT,
    icon VARCHAR
        (50),
    color VARCHAR
        (20),
    image VARCHAR
        (255),
    display_order INT DEFAULT 0,
    is_featured TINYINT
        (1) DEFAULT 0,
    status ENUM
        ('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
        UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY
        (parent_id) REFERENCES categories
        (id) ON
        DELETE CASCADE,
    INDEX idx_parent (parent_id),
    INDEX idx_slug
        (slug),
    INDEX idx_status
        (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        -- ============================================
        -- TABLE: SCHOOL LEVELS
        -- ============================================
        CREATE TABLE school_levels
        (
            id INT
            UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR
            (100) NOT NULL,
    slug VARCHAR
            (100) NOT NULL UNIQUE,
    level_order INT NOT NULL,
    category_id INT UNSIGNED,
    description TEXT,
    age_range VARCHAR
            (50),
    status ENUM
            ('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
            UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY
            (category_id) REFERENCES categories
            (id) ON
            DELETE
            SET NULL
            ,
    INDEX idx_level_order
            (level_order),
    INDEX idx_status
            (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            -- ============================================
            -- TABLE: SCHOOL SUBJECTS
            -- ============================================
            CREATE TABLE school_subjects
            (
                id INT
                UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR
                (100) NOT NULL,
    slug VARCHAR
                (100) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR
                (50),
    color VARCHAR
                (20),
    display_order INT DEFAULT 0,
    status ENUM
                ('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
                UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                -- ============================================
                -- TABLE: BOOKS
                -- ============================================
                CREATE TABLE books
                (
                    id INT
                    UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    isbn VARCHAR
                    (13) UNIQUE,
    title VARCHAR
                    (255) NOT NULL,
    author VARCHAR
                    (255) NOT NULL,
    publisher VARCHAR
                    (255),
    publication_year YEAR,
    category VARCHAR
                    (100),
    category_id INT UNSIGNED,
    description TEXT,
    cover_image VARCHAR
                    (255),
    total_copies INT UNSIGNED NOT NULL DEFAULT 1,
    available_copies INT UNSIGNED NOT NULL DEFAULT 1,
    status ENUM
                    ('active', 'inactive') NOT NULL DEFAULT 'active',
    created_by INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
                    UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY
                    (created_by) REFERENCES users
                    (id) ON
                    DELETE
                    SET NULL
                    ,
    FOREIGN KEY
                    (category_id) REFERENCES categories
                    (id) ON
                    DELETE
                    SET NULL
                    ,
    INDEX idx_title
                    (title),
    INDEX idx_author
                    (author),
    INDEX idx_category
                    (category),
    INDEX idx_category_id
                    (category_id),
    INDEX idx_status
                    (status),
    FULLTEXT idx_search
                    (title, author, description),
    FULLTEXT idx_books_search
                    (title, author, description, publisher)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                    -- ============================================
                    -- TABLE: BOOK SCHOOL LEVELS
                    -- ============================================
                    CREATE TABLE book_school_levels
                    (
                        id INT
                        UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id INT UNSIGNED NOT NULL,
    school_level_id INT UNSIGNED NOT NULL,
    FOREIGN KEY
                        (book_id) REFERENCES books
                        (id) ON
                        DELETE CASCADE,
    FOREIGN KEY (school_level_id)
                        REFERENCES school_levels
                        (id) ON
                        DELETE CASCADE,
    UNIQUE KEY unique_book_level (book_id, school_level_id
                        )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                        -- ============================================
                        -- TABLE: BOOK SUBJECTS
                        -- ============================================
                        CREATE TABLE book_subjects
                        (
                            id INT
                            UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id INT UNSIGNED NOT NULL,
    subject_id INT UNSIGNED NOT NULL,
    FOREIGN KEY
                            (book_id) REFERENCES books
                            (id) ON
                            DELETE CASCADE,
    FOREIGN KEY (subject_id)
                            REFERENCES school_subjects
                            (id) ON
                            DELETE CASCADE,
    UNIQUE KEY unique_book_subject (book_id, subject_id
                            )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                            -- ============================================
                            -- TABLE: EVENTS
                            -- ============================================
                            CREATE TABLE events
                            (
                                id INT
                                UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR
                                (255) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    event_time TIME,
    location VARCHAR
                                (255),
    max_participants INT UNSIGNED,
    current_participants INT UNSIGNED DEFAULT 0,
    image VARCHAR
                                (255),
    status ENUM
                                ('upcoming', 'ongoing', 'completed', 'cancelled') NOT NULL DEFAULT 'upcoming',
    created_by INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
                                UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY
                                (created_by) REFERENCES users
                                (id) ON
                                DELETE
                                SET NULL
                                ,
    INDEX idx_event_date
                                (event_date),
    INDEX idx_status
                                (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                -- ============================================
                                -- TABLE: BORROWINGS
                                -- ============================================
                                CREATE TABLE borrowings
                                (
                                    id INT
                                    UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    book_id INT UNSIGNED NOT NULL,
    borrowed_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE NULL,
    status ENUM
                                    ('active', 'returned', 'overdue', 'lost') NOT NULL DEFAULT 'active',
    notes TEXT,
    processed_by INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
                                    UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY
                                    (user_id) REFERENCES users
                                    (id) ON
                                    DELETE CASCADE,
    FOREIGN KEY (book_id)
                                    REFERENCES books
                                    (id) ON
                                    DELETE CASCADE,
    FOREIGN KEY (processed_by)
                                    REFERENCES users
                                    (id) ON
                                    DELETE
                                    SET NULL
                                    ,
    INDEX idx_user_id
                                    (user_id),
    INDEX idx_book_id
                                    (book_id),
    INDEX idx_status
                                    (status),
    INDEX idx_due_date
                                    (due_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                    -- ============================================
                                    -- TABLE: REVIEWS
                                    -- ============================================
                                    CREATE TABLE reviews
                                    (
                                        id INT
                                        UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL CHECK
                                        (rating BETWEEN 1 AND 5),
    review_text TEXT,
    status ENUM
                                        ('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    moderated_by INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
                                        UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY
                                        (book_id) REFERENCES books
                                        (id) ON
                                        DELETE CASCADE,
    FOREIGN KEY (user_id)
                                        REFERENCES users
                                        (id) ON
                                        DELETE CASCADE,
    FOREIGN KEY (moderated_by)
                                        REFERENCES users
                                        (id) ON
                                        DELETE
                                        SET NULL
                                        ,
    UNIQUE KEY unique_user_book_review
                                        (book_id, user_id),
    INDEX idx_book_id
                                        (book_id),
    INDEX idx_user_id
                                        (user_id),
    INDEX idx_status
                                        (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                        -- ============================================
                                        -- TABLE: COMMENTS
                                        -- ============================================
                                        CREATE TABLE comments
                                        (
                                            id INT
                                            UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    commentable_type ENUM
                                            ('book', 'event') NOT NULL,
    commentable_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    comment_text TEXT NOT NULL,
    status ENUM
                                            ('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    moderated_by INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
                                            UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY
                                            (user_id) REFERENCES users
                                            (id) ON
                                            DELETE CASCADE,
    FOREIGN KEY (moderated_by)
                                            REFERENCES users
                                            (id) ON
                                            DELETE
                                            SET NULL
                                            ,
    INDEX idx_commentable
                                            (commentable_type, commentable_id),
    INDEX idx_user_id
                                            (user_id),
    INDEX idx_status
                                            (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                            -- ============================================
                                            -- TABLE: REACTIONS
                                            -- ============================================
                                            CREATE TABLE reactions
                                            (
                                                id INT
                                                UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reactable_type ENUM
                                                ('review', 'comment') NOT NULL,
    reactable_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    reaction_type ENUM
                                                ('like', 'dislike') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY
                                                (user_id) REFERENCES users
                                                (id) ON
                                                DELETE CASCADE,
    UNIQUE KEY unique_user_reaction (reactable_type, reactable_id, user_id
                                                ),
    INDEX idx_reactable
                                                (reactable_type, reactable_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                                -- ============================================
                                                -- TABLE: ANNOUNCEMENTS
                                                -- ============================================
                                                CREATE TABLE announcements
                                                (
                                                    id INT
                                                    UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR
                                                    (255) NOT NULL,
    content TEXT NOT NULL,
    type ENUM
                                                    ('info', 'warning', 'success', 'danger') NOT NULL DEFAULT 'info',
    status ENUM
                                                    ('active', 'inactive') NOT NULL DEFAULT 'active',
    display_order INT DEFAULT 0,
    created_by INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
                                                    UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY
                                                    (created_by) REFERENCES users
                                                    (id) ON
                                                    DELETE
                                                    SET NULL
                                                    ,
    INDEX idx_status
                                                    (status),
    INDEX idx_display_order
                                                    (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                                    -- ============================================
                                                    -- TABLE: NEWS
                                                    -- ============================================
                                                    CREATE TABLE news
                                                    (
                                                        id INT
                                                        UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR
                                                        (255) NOT NULL,
    subtitle VARCHAR
                                                        (255),
    content TEXT,
    image VARCHAR
                                                        (255),
    link VARCHAR
                                                        (255),
    display_order INT DEFAULT 0,
    status ENUM
                                                        ('active', 'inactive') NOT NULL DEFAULT 'active',
    created_by INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
                                                        UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY
                                                        (created_by) REFERENCES users
                                                        (id) ON
                                                        DELETE
                                                        SET NULL
                                                        ,
    INDEX idx_status
                                                        (status),
    INDEX idx_display_order
                                                        (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                                        -- ============================================
                                                        -- TABLE: EVENT PARTICIPANTS
                                                        -- ============================================
                                                        CREATE TABLE event_participants
                                                        (
                                                            id INT
                                                            UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM
                                                            ('registered', 'attended', 'cancelled') NOT NULL DEFAULT 'registered',
    FOREIGN KEY
                                                            (event_id) REFERENCES events
                                                            (id) ON
                                                            DELETE CASCADE,
    FOREIGN KEY (user_id)
                                                            REFERENCES users
                                                            (id) ON
                                                            DELETE CASCADE,
    UNIQUE KEY unique_event_user (event_id, user_id
                                                            ),
    INDEX idx_event_id
                                                            (event_id),
    INDEX idx_user_id
                                                            (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                                            -- ============================================
                                                            -- TABLE: ADVERTISEMENTS
                                                            -- ============================================
                                                            CREATE TABLE advertisements
                                                            (
                                                                id INT
                                                                UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR
                                                                (255) NOT NULL,
    company_name VARCHAR
                                                                (255) NOT NULL,
    contact_email VARCHAR
                                                                (255) NOT NULL,
    contact_phone VARCHAR
                                                                (20),
    ad_type ENUM
                                                                ('banner_top', 'banner_side', 'banner_bottom', 'popup', 'inline') NOT NULL DEFAULT 'banner_side',
    image_url VARCHAR
                                                                (255),
    link_url VARCHAR
                                                                (255) NOT NULL,
    description TEXT,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM
                                                                ('pending', 'active', 'paused', 'expired', 'rejected') NOT NULL DEFAULT 'pending',
    impressions INT UNSIGNED DEFAULT 0,
    clicks INT UNSIGNED DEFAULT 0,
    price DECIMAL
                                                                (10, 2) DEFAULT 0.00,
    payment_status ENUM
                                                                ('unpaid', 'paid', 'refunded') DEFAULT 'unpaid',
    display_pages TEXT,
    created_by INT UNSIGNED,
    approved_by INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
                                                                UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY
                                                                (created_by) REFERENCES users
                                                                (id) ON
                                                                DELETE
                                                                SET NULL
                                                                ,
    FOREIGN KEY
                                                                (approved_by) REFERENCES users
                                                                (id) ON
                                                                DELETE
                                                                SET NULL
                                                                ,
    INDEX idx_status
                                                                (status),
    INDEX idx_dates
                                                                (start_date, end_date),
    INDEX idx_type
                                                                (ad_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                                                -- ============================================
                                                                -- TABLE: AD TRACKING
                                                                -- ============================================
                                                                CREATE TABLE ad_tracking
                                                                (
                                                                    id INT
                                                                    UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ad_id INT UNSIGNED NOT NULL,
    event_type ENUM
                                                                    ('impression', 'click') NOT NULL,
    user_id INT UNSIGNED,
    ip_address VARCHAR
                                                                    (45),
    user_agent TEXT,
    page_url VARCHAR
                                                                    (255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY
                                                                    (ad_id) REFERENCES advertisements
                                                                    (id) ON
                                                                    DELETE CASCADE,
    FOREIGN KEY (user_id)
                                                                    REFERENCES users
                                                                    (id) ON
                                                                    DELETE
                                                                    SET NULL
                                                                    ,
    INDEX idx_ad_event
                                                                    (ad_id, event_type),
    INDEX idx_created
                                                                    (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                                                    -- ============================================
                                                                    -- TABLE: EXERCISES
                                                                    -- ============================================
                                                                    CREATE TABLE exercises
                                                                    (
                                                                        id INT
                                                                        UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR
                                                                        (255) NOT NULL,
    description TEXT,
    school_level_id INT UNSIGNED NOT NULL,
    subject_id INT UNSIGNED NOT NULL,
    book_id INT UNSIGNED,
    difficulty ENUM
                                                                        ('facile', 'moyen', 'difficile') DEFAULT 'moyen',
    duration_minutes INT,
    file_path VARCHAR
                                                                        (255),
    file_type ENUM
                                                                        ('pdf', 'doc', 'docx', 'image') DEFAULT 'pdf',
    has_solutions TINYINT
                                                                        (1) DEFAULT 0,
    solutions_file VARCHAR
                                                                        (255),
    view_count INT UNSIGNED DEFAULT 0,
    download_count INT UNSIGNED DEFAULT 0,
    status ENUM
                                                                        ('active', 'inactive') DEFAULT 'active',
    created_by INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
                                                                        UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY
                                                                        (school_level_id) REFERENCES school_levels
                                                                        (id) ON
                                                                        DELETE CASCADE,
    FOREIGN KEY (subject_id)
                                                                        REFERENCES school_subjects
                                                                        (id) ON
                                                                        DELETE CASCADE,
    FOREIGN KEY (book_id)
                                                                        REFERENCES books
                                                                        (id) ON
                                                                        DELETE
                                                                        SET NULL
                                                                        ,
    FOREIGN KEY
                                                                        (created_by) REFERENCES users
                                                                        (id) ON
                                                                        DELETE
                                                                        SET NULL
                                                                        ,
    INDEX idx_level_subject
                                                                        (school_level_id, subject_id),
    INDEX idx_difficulty
                                                                        (difficulty),
    INDEX idx_status
                                                                        (status),
    FULLTEXT idx_exercises_search
                                                                        (title, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                                                        -- ============================================
                                                                        -- TABLE: EXERCISE TAGS
                                                                        -- ============================================
                                                                        CREATE TABLE exercise_tags
                                                                        (
                                                                            id INT
                                                                            UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR
                                                                            (50) NOT NULL UNIQUE,
    slug VARCHAR
                                                                            (50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                                                            -- ============================================
                                                                            -- TABLE: EXERCISE TAG RELATIONS
                                                                            -- ============================================
                                                                            CREATE TABLE exercise_tag_relations
                                                                            (
                                                                                id INT
                                                                                UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    exercise_id INT UNSIGNED NOT NULL,
    tag_id INT UNSIGNED NOT NULL,
    FOREIGN KEY
                                                                                (exercise_id) REFERENCES exercises
                                                                                (id) ON
                                                                                DELETE CASCADE,
    FOREIGN KEY (tag_id)
                                                                                REFERENCES exercise_tags
                                                                                (id) ON
                                                                                DELETE CASCADE,
    UNIQUE KEY unique_exercise_tag (exercise_id, tag_id
                                                                                )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                                                                -- ============================================
                                                                                -- TABLE: SEARCH HISTORY
                                                                                -- ============================================
                                                                                CREATE TABLE search_history
                                                                                (
                                                                                    id INT
                                                                                    UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    search_query VARCHAR
                                                                                    (255) NOT NULL,
    search_type ENUM
                                                                                    ('books', 'exercises', 'events', 'all') DEFAULT 'all',
    filters JSON,
    results_count INT UNSIGNED DEFAULT 0,
    ip_address VARCHAR
                                                                                    (45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY
                                                                                    (user_id) REFERENCES users
                                                                                    (id) ON
                                                                                    DELETE
                                                                                    SET NULL
                                                                                    ,
    INDEX idx_user_search
                                                                                    (user_id, created_at),
    INDEX idx_query
                                                                                    (search_query),
    FULLTEXT idx_query_fulltext
                                                                                    (search_query)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                                                                                    -- ============================================
                                                                                    -- TABLE: POPULAR SEARCHES
                                                                                    -- ============================================
                                                                                    CREATE TABLE popular_searches
                                                                                    (
                                                                                        id INT
                                                                                        UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    search_term VARCHAR
                                                                                        (255) NOT NULL UNIQUE,
    search_count INT UNSIGNED DEFAULT 1,
    last_searched TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
                                                                                        UPDATE CURRENT_TIMESTAMP,
    INDEX idx_count (search_count),
    INDEX idx_last_searched (last_searched)
                                                                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;