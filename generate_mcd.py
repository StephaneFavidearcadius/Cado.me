#!/usr/bin/env python3
"""
Générateur de MCD (Modèle Conceptuel de Données) pour Cado.me
Génère un PDF professionnel avec toutes les entités et relations
"""

from reportlab.lib.pagesizes import A4, landscape
from reportlab.lib import colors
from reportlab.lib.units import mm, cm
from reportlab.pdfgen import canvas
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.platypus import Paragraph
import math

# Configuration
PAGE_WIDTH, PAGE_HEIGHT = landscape(A4)
MARGIN = 15 * mm

# Couleurs
VIOLET = colors.HexColor('#7830E0')
VIOLET_FONCE = colors.HexColor('#6420C7')
VIOLET_CLAIR = colors.HexColor('#9B5DEB')
VIOLET_TRES_CLAIR = colors.HexColor('#F3EAFF')
BLANC = colors.HexColor('#FFFFFF')
GRIS = colors.HexColor('#F5F5F5')
GRIS_FONCE = colors.HexColor('#333333')
GRIS_MOYEN = colors.HexColor('#666666')
GRIS_CLAIR = colors.HexColor('#E5E5E5')

# Styles
styles = getSampleStyleSheet()

def draw_header(c, y):
    """Dessiner l'en-tête du document"""
    # Fond violet
    c.setFillColor(VIOLET)
    c.rect(0, y - 25*mm, PAGE_WIDTH, 30*mm, fill=1, stroke=0)
    
    # Titre
    c.setFillColor(BLANC)
    c.setFont("Helvetica-Bold", 22)
    c.drawCentredString(PAGE_WIDTH/2, y - 10*mm, "MODÈLE CONCEPTUEL DE DONNÉES (MCD)")
    
    # Sous-titre
    c.setFont("Helvetica", 14)
    c.drawCentredString(PAGE_WIDTH/2, y - 18*mm, "Cado.me - Plateforme SaaS Multi-Communautés")
    
    # Date
    c.setFont("Helvetica", 10)
    c.drawCentredString(PAGE_WIDTH/2, y - 23*mm, "Généré le 31 août 2026")
    
    return y - 30*mm


def draw_entity_box(c, x, y, entity_name, attributes, width=55*mm, max_height=80*mm):
    """Dessiner une boîte d'entité"""
    # Calculer la hauteur nécessaire
    header_height = 8*mm
    attr_height = 4.5*mm
    total_height = header_height + len(attributes) * attr_height + 4*mm
    
    # Limiter la hauteur maximale
    if total_height > max_height:
        total_height = max_height
    
    # Ombre
    c.setFillColor(GRIS_CLAIR)
    c.setStrokeColor(GRIS_CLAIR)
    c.roundRect(x + 1*mm, y - total_height - 1*mm, width, total_height, 3*mm, fill=1, stroke=0)
    
    # Fond blanc
    c.setFillColor(BLANC)
    c.setStrokeColor(VIOLET_FONCE)
    c.setLineWidth(1.5)
    c.roundRect(x, y - total_height, width, total_height, 3*mm, fill=1, stroke=1)
    
    # En-tête de l'entité
    c.setFillColor(VIOLET)
    c.roundRect(x, y - header_height, width, header_height, 3*mm, fill=1, stroke=0)
    c.rect(x, y - header_height, width, header_height/2, fill=1, stroke=0)
    
    c.setFillColor(BLANC)
    c.setFont("Helvetica-Bold", 9)
    c.drawCentredString(x + width/2, y - 5.5*mm, entity_name.upper())
    
    # Attributs
    c.setFont("Helvetica", 7)
    attr_y = y - header_height - 3*mm
    
    for i, attr in enumerate(attributes[:18]):  # Limiter à 18 attributs visibles
        if attr_y < y - total_height + 2*mm:
            break
        
        # Couleur alternée
        if i % 2 == 0:
            c.setFillColor(GRIS)
            c.rect(x + 1*mm, attr_y - 2.5*mm, width - 2*mm, attr_height, fill=1, stroke=0)
        
        # Icône clé primaire ou étrangère
        c.setFillColor(VIOLET_CLAIR)
        if attr.startswith('PK'):
            c.setFillColor(VIOLET_FONCE)
            attr_text = attr[3:].strip()
            c.setFont("Helvetica-Bold", 7)
        elif attr.startswith('FK'):
            c.setFillColor(GRIS_MOYEN)
            attr_text = attr[3:].strip()
            c.setFont("Helvetica", 7)
        else:
            attr_text = attr
            c.setFont("Helvetica", 7)
        
        c.setFillColor(GRIS_FONCE)
        c.drawString(x + 3*mm, attr_y - 2*mm, attr_text)
        attr_y -= attr_height
    
    # Indicateur "plus d'attributs"
    if len(attributes) > 18:
        c.setFillColor(GRIS_MOYEN)
        c.setFont("Helvetica-Oblique", 7)
        c.drawCentredString(x + width/2, y - total_height + 3*mm, f"+ {len(attributes) - 18} autres...")
    
    return (x + width/2, y - total_height)  # Retourner le centre inférieur


def draw_relationship_line(c, x1, y1, x2, y2, cardinality1, cardinality2, label=""):
    """Dessiner une ligne de relation avec cardinalités"""
    c.setStrokeColor(VIOLET_CLAIR)
    c.setLineWidth(1.2)
    
    # Calculer l'angle
    angle = math.atan2(y2 - y1, x2 - x1)
    
    # Points de départ et d'arrivée (ajustés pour ne pas entrer dans les boîtes)
    start_x = x1 + 3*mm * math.cos(angle)
    start_y = y1 + 3*mm * math.sin(angle)
    end_x = x2 - 3*mm * math.cos(angle)
    end_y = y2 - 3*mm * math.sin(angle)
    
    # Dessiner la ligne
    c.line(start_x, start_y, end_x, end_y)
    
    # Cardinalités
    c.setFillColor(VIOLET_FONCE)
    c.setFont("Helvetica-Bold", 8)
    
    # Cardinalité au début
    c.drawCentredString(start_x + 4*mm * math.cos(angle), 
                        start_y + 4*mm * math.sin(angle), cardinality1)
    
    # Cardinalité à la fin
    c.drawCentredString(end_x - 4*mm * math.cos(angle), 
                        end_y - 4*mm * math.sin(angle), cardinality2)
    
    # Label de la relation
    if label:
        mid_x = (start_x + end_x) / 2
        mid_y = (start_y + end_y) / 2
        c.setFillColor(GRIS_MOYEN)
        c.setFont("Helvetica", 7)
        c.drawCentredString(mid_x, mid_y + 2*mm, label)


def draw_page1(c):
    """Page 1: Entités principales"""
    y = PAGE_HEIGHT - 15*mm
    
    # En-tête
    y = draw_header(c, y)
    y -= 10*mm
    
    # Titre de la section
    c.setFillColor(VIOLET)
    c.setFont("Helvetica-Bold", 14)
    c.drawString(MARGIN, y, "1. ENTITÉS PRINCIPALES")
    y -= 10*mm
    
    # UTILISATEURS
    attrs_users = [
        "PK id (INT UNSIGNED)",
        "prenom (VARCHAR 100)",
        "nom (VARCHAR 100)",
        "identifiant (VARCHAR 100) UNIQUE",
        "email (VARCHAR 255) UNIQUE",
        "mot_de_passe (VARCHAR 255)",
        "avatar (VARCHAR 500)",
        "biographie (TEXT)",
        "role_plateforme (ENUM)",
        "statut (ENUM)",
        "email_verifie (TINYINT)",
        "date_creation (DATETIME)",
        "date_modification (DATETIME)"
    ]
    draw_entity_box(c, MARGIN, y, "UTILISATEURS", attrs_users)
    
    # COMMUNAUTES
    attrs_comm = [
        "PK id (INT UNSIGNED)",
        "FK proprietaire_id (INT UNSIGNED)",
        "nom (VARCHAR 100)",
        "slug (VARCHAR 120) UNIQUE",
        "description (TEXT)",
        "logo (VARCHAR 500)",
        "image_couverture (VARCHAR 500)",
        "couleur_principale (VARCHAR 7)",
        "couleur_secondaire (VARCHAR 7)",
        "statut (ENUM)",
        "visibilite (ENUM)",
        "parametres (JSON)",
        "date_creation (DATETIME)",
        "date_modification (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 65*mm, y, "COMMUNAUTÉS", attrs_comm)
    
    # MEMBRES_COMMUNAUTES
    attrs_membres = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "FK utilisateur_id (INT UNSIGNED)",
        "role (ENUM)",
        "statut (ENUM)",
        "date_adhesion (DATETIME)",
        "date_derniere_activite (DATETIME)",
        "date_modification (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 130*mm, y, "MEMBRES COMMUNAUTÉS", attrs_membres)
    
    # PLANS
    attrs_plans = [
        "PK id (INT UNSIGNED)",
        "nom (VARCHAR 100)",
        "description (TEXT)",
        "prix_mensuel (DECIMAL 8,2)",
        "prix_annuel (DECIMAL 8,2)",
        "limite_membres (INT UNSIGNED)",
        "limite_stockage (BIGINT UNSIGNED)",
        "limite_formations (INT UNSIGNED)",
        "limite_communautes (INT UNSIGNED)",
        "actif (TINYINT)",
        "date_creation (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 195*mm, y, "PLANS", attrs_plans)
    
    # ABONNEMENTS
    attrs_abo = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "FK plan_id (INT UNSIGNED)",
        "statut (ENUM)",
        "periode_debut (DATE)",
        "periode_fin (DATE)",
        "fournisseur (VARCHAR 50)",
        "identifiant_externe (VARCHAR 255)",
        "date_creation (DATETIME)",
        "date_modification (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 260*mm, y, "ABONNEMENTS", attrs_abo)
    
    # Dessiner les relations de la page 1
    # UTILISATEURS 1--* COMMUNAUTES (propriétaire)
    draw_relationship_line(c, MARGIN + 55*mm, y - 30*mm, MARGIN + 65*mm, y - 30*mm, "1", "*", "possède")
    
    # UTILISATEURS *--* COMMUNAUTES (via membres)
    draw_relationship_line(c, MARGIN + 55*mm, y - 50*mm, MARGIN + 130*mm, y - 50*mm, "*", "*", "adhère à")
    
    # COMMUNAUTES 1--* ABONNEMENTS
    draw_relationship_line(c, MARGIN + 120*mm, y - 30*mm, MARGIN + 260*mm, y - 30*mm, "1", "*", "souscrit")
    
    # PLANS 1--* ABONNEMENTS
    draw_relationship_line(c, MARGIN + 285*mm, y - 10*mm, MARGIN + 285*mm, y - 30*mm, "1", "*", "définit")
    
    # Pied de page
    c.setFillColor(GRIS_MOYEN)
    c.setFont("Helvetica", 8)
    c.drawString(MARGIN, 10*mm, "Cado.me - MCD - Page 1/4")
    c.drawRightString(PAGE_WIDTH - MARGIN, 10*mm, "Modèle Conceptuel de Données")


def draw_page2(c):
    """Page 2: Publications, Médias, Commentaires, Likes"""
    y = PAGE_HEIGHT - 15*mm
    
    # En-tête
    y = draw_header(c, y)
    y -= 10*mm
    
    # Titre de la section
    c.setFillColor(VIOLET)
    c.setFont("Helvetica-Bold", 14)
    c.drawString(MARGIN, y, "2. CONTENUS ET INTERACTIONS")
    y -= 10*mm
    
    # PUBLICATIONS
    attrs_pub = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "FK utilisateur_id (INT UNSIGNED)",
        "contenu (TEXT)",
        "type (ENUM)",
        "statut (ENUM)",
        "date_creation (DATETIME)",
        "date_modification (DATETIME)"
    ]
    draw_entity_box(c, MARGIN, y, "PUBLICATIONS", attrs_pub)
    
    # MEDIAS_PUBLICATIONS
    attrs_medias = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "FK publication_id (INT UNSIGNED)",
        "type (ENUM)",
        "nom_fichier (VARCHAR 255)",
        "nom_stockage (VARCHAR 255)",
        "chemin (VARCHAR 500)",
        "url (VARCHAR 500)",
        "mime_type (VARCHAR 100)",
        "taille (INT UNSIGNED)",
        "largeur (INT UNSIGNED)",
        "hauteur (INT UNSIGNED)",
        "ordre (INT)",
        "date_creation (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 65*mm, y, "MÉDIAS PUBLICATIONS", attrs_medias)
    
    # COMMENTAIRES
    attrs_comment = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "FK publication_id (INT UNSIGNED)",
        "FK utilisateur_id (INT UNSIGNED)",
        "FK commentaire_parent_id (INT UNSIGNED)",
        "contenu (TEXT)",
        "statut (ENUM)",
        "date_creation (DATETIME)",
        "date_modification (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 130*mm, y, "COMMENTAIRES", attrs_comment)
    
    # LIKES_PUBLICATIONS
    attrs_likes = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "FK publication_id (INT UNSIGNED)",
        "FK utilisateur_id (INT UNSIGNED)",
        "date_creation (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 195*mm, y, "LIKES PUBLICATIONS", attrs_likes)
    
    # SIGNALEMENTS
    attrs_sign = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "FK utilisateur_id (INT UNSIGNED)",
        "FK publication_id (INT UNSIGNED)",
        "FK commentaire_id (INT UNSIGNED)",
        "motif (VARCHAR 500)",
        "statut (ENUM)",
        "date_creation (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 260*mm, y, "SIGNALEMENTS", attrs_sign)
    
    # Dessiner les relations
    # COMMUNAUTES 1--* PUBLICATIONS
    draw_relationship_line(c, MARGIN + 50*mm, y - 30*mm, MARGIN + 50*mm, y - 50*mm, "1", "*", "publie")
    
    # UTILISATEURS 1--* PUBLICATIONS
    draw_relationship_line(c, MARGIN + 20*mm, y - 30*mm, MARGIN + 20*mm, y - 50*mm, "1", "*", "rédige")
    
    # PUBLICATIONS 1--* MEDIAS
    draw_relationship_line(c, MARGIN + 55*mm, y - 30*mm, MARGIN + 65*mm, y - 30*mm, "1", "*", "contient")
    
    # PUBLICATIONS 1--* COMMENTAIRES
    draw_relationship_line(c, MARGIN + 55*mm, y - 50*mm, MARGIN + 130*mm, y - 50*mm, "1", "*", "reçoit")
    
    # PUBLICATIONS 1--* LIKES
    draw_relationship_line(c, MARGIN + 55*mm, y - 70*mm, MARGIN + 195*mm, y - 70*mm, "1", "*", "est likée")
    
    # COMMENTAIRES 0..1--* SIGNALEMENTS
    draw_relationship_line(c, MARGIN + 185*mm, y - 50*mm, MARGIN + 260*mm, y - 50*mm, "0..1", "*", "est signalé")
    
    # COMMENTAIRES auto-référence
    c.setFillColor(VIOLET_CLAIR)
    c.setFont("Helvetica", 7)
    c.drawString(MARGIN + 140*mm, y - 90*mm, "↳ Réponse à commentaire parent")
    
    # Pied de page
    c.setFillColor(GRIS_MOYEN)
    c.setFont("Helvetica", 8)
    c.drawString(MARGIN, 10*mm, "Cado.me - MCD - Page 2/4")
    c.drawRightString(PAGE_WIDTH - MARGIN, 10*mm, "Modèle Conceptuel de Données")


def draw_page3(c):
    """Page 3: Formations, Leçons, Progression, Ressources, Événements"""
    y = PAGE_HEIGHT - 15*mm
    
    # En-tête
    y = draw_header(c, y)
    y -= 10*mm
    
    # Titre de la section
    c.setFillColor(VIOLET)
    c.setFont("Helvetica-Bold", 14)
    c.drawString(MARGIN, y, "3. FORMATIONS, RESSOURCES ET ÉVÉNEMENTS")
    y -= 10*mm
    
    # FORMATIONS
    attrs_form = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "titre (VARCHAR 200)",
        "slug (VARCHAR 220)",
        "description (TEXT)",
        "image (VARCHAR 500)",
        "statut (ENUM)",
        "ordre (INT)",
        "date_creation (DATETIME)",
        "date_modification (DATETIME)"
    ]
    draw_entity_box(c, MARGIN, y, "FORMATIONS", attrs_form)
    
    # LECONS
    attrs_lecons = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "FK formation_id (INT UNSIGNED)",
        "titre (VARCHAR 200)",
        "slug (VARCHAR 220)",
        "description (TEXT)",
        "contenu (LONGTEXT)",
        "video_url (VARCHAR 500)",
        "ordre (INT)",
        "statut (ENUM)",
        "date_creation (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 65*mm, y, "LEÇONS", attrs_lecons)
    
    # PROGRESSION_FORMATIONS
    attrs_prog = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "FK utilisateur_id (INT UNSIGNED)",
        "FK lecon_id (INT UNSIGNED)",
        "terminee (TINYINT)",
        "date_completion (DATETIME)",
        "date_creation (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 130*mm, y, "PROGRESSION FORMATIONS", attrs_prog)
    
    # RESSOURCES
    attrs_res = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "titre (VARCHAR 200)",
        "description (TEXT)",
        "type (ENUM)",
        "chemin (VARCHAR 500)",
        "url (VARCHAR 500)",
        "nom_fichier (VARCHAR 255)",
        "statut (ENUM)",
        "ordre (INT)",
        "date_creation (DATETIME)",
        "date_modification (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 195*mm, y, "RESSOURCES", attrs_res)
    
    # ÉVÉNEMENTS
    attrs_event = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "titre (VARCHAR 200)",
        "slug (VARCHAR 220)",
        "description (TEXT)",
        "date_debut (DATETIME)",
        "date_fin (DATETIME)",
        "type (ENUM)",
        "lien (VARCHAR 500)",
        "image (VARCHAR 500)",
        "statut (ENUM)",
        "date_creation (DATETIME)",
        "date_modification (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 260*mm, y, "ÉVÉNEMENTS", attrs_event)
    
    # Dessiner les relations
    # COMMUNAUTES 1--* FORMATIONS
    draw_relationship_line(c, MARGIN + 50*mm, y - 30*mm, MARGIN + 50*mm, y - 50*mm, "1", "*", "propose")
    
    # FORMATIONS 1--* LECONS
    draw_relationship_line(c, MARGIN + 55*mm, y - 30*mm, MARGIN + 65*mm, y - 30*mm, "1", "*", "contient")
    
    # LECONS 1--* PROGRESSION
    draw_relationship_line(c, MARGIN + 120*mm, y - 30*mm, MARGIN + 130*mm, y - 30*mm, "1", "*", "est suivie")
    
    # UTILISATEURS 1--* PROGRESSION
    draw_relationship_line(c, MARGIN + 145*mm, y - 50*mm, MARGIN + 145*mm, y - 70*mm, "1", "*", "progresse")
    
    # COMMUNAUTES 1--* RESSOURCES
    draw_relationship_line(c, MARGIN + 245*mm, y - 30*mm, MARGIN + 195*mm, y - 30*mm, "1", "*", "met à disposition")
    
    # COMMUNAUTES 1--* EVENEMENTS
    draw_relationship_line(c, MARGIN + 120*mm, y - 30*mm, MARGIN + 260*mm, y - 30*mm, "1", "*", "organise")
    
    # Pied de page
    c.setFillColor(GRIS_MOYEN)
    c.setFont("Helvetica", 8)
    c.drawString(MARGIN, 10*mm, "Cado.me - MCD - Page 3/4")
    c.drawRightString(PAGE_WIDTH - MARGIN, 10*mm, "Modèle Conceptuel de Données")


def draw_page4(c):
    """Page 4: Notifications, Messagerie, Invitations, Audit"""
    y = PAGE_HEIGHT - 15*mm
    
    # En-tête
    y = draw_header(c, y)
    y -= 10*mm
    
    # Titre de la section
    c.setFillColor(VIOLET)
    c.setFont("Helvetica-Bold", 14)
    c.drawString(MARGIN, y, "4. NOTIFICATIONS, MESSAGERIE ET SYSTÈME")
    y -= 10*mm
    
    # NOTIFICATIONS
    attrs_notif = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "FK utilisateur_id (INT UNSIGNED)",
        "type (VARCHAR 50)",
        "titre (VARCHAR 200)",
        "message (TEXT)",
        "lien (VARCHAR 500)",
        "lue (TINYINT)",
        "date_creation (DATETIME)"
    ]
    draw_entity_box(c, MARGIN, y, "NOTIFICATIONS", attrs_notif)
    
    # CONVERSATIONS
    attrs_conv = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "date_creation (DATETIME)",
        "date_modification (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 65*mm, y, "CONVERSATIONS", attrs_conv)
    
    # PARTICIPANTS_CONVERSATIONS
    attrs_part = [
        "PK FK conversation_id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "FK utilisateur_id (INT UNSIGNED)",
        "date_creation (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 130*mm, y, "PARTICIPANTS CONVERSATIONS", attrs_part)
    
    # MESSAGES
    attrs_msg = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "FK conversation_id (INT UNSIGNED)",
        "FK utilisateur_id (INT UNSIGNED)",
        "contenu (TEXT)",
        "lu (TINYINT)",
        "date_creation (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 195*mm, y, "MESSAGES", attrs_msg)
    
    # INVITATIONS_COMMUNAUTES
    attrs_invit = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "email (VARCHAR 255)",
        "token (VARCHAR 64) UNIQUE",
        "role (ENUM)",
        "expire_le (DATETIME)",
        "acceptee (TINYINT)",
        "date_creation (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 260*mm, y, "INVITATIONS COMMUNAUTÉS", attrs_invit)
    
    # JOURNAUX_AUDIT (en bas)
    attrs_audit = [
        "PK id (INT UNSIGNED)",
        "FK communaute_id (INT UNSIGNED)",
        "FK utilisateur_id (INT UNSIGNED)",
        "action (VARCHAR 100)",
        "entite (VARCHAR 100)",
        "entite_id (INT UNSIGNED)",
        "donnees (JSON)",
        "adresse_ip (VARCHAR 45)",
        "date_creation (DATETIME)"
    ]
    draw_entity_box(c, MARGIN, y - 70*mm, "JOURNAUX AUDIT", attrs_audit)
    
    # PARAMETRES_PLATEFORME
    attrs_param = [
        "PK id (INT UNSIGNED) DEFAULT 1",
        "nom_plateforme (VARCHAR 100)",
        "description_plateforme (TEXT)",
        "email_contact (VARCHAR 255)",
        "maintenance (TINYINT)",
        "date_creation (DATETIME)",
        "date_modification (DATETIME)"
    ]
    draw_entity_box(c, MARGIN + 130*mm, y - 70*mm, "PARAMÈTRES PLATEFORME", attrs_param)
    
    # Dessiner les relations
    # UTILISATEURS 1--* NOTIFICATIONS
    draw_relationship_line(c, MARGIN + 15*mm, y - 30*mm, MARGIN + 15*mm, y - 50*mm, "1", "*", "reçoit")
    
    # COMMUNAUTES 1--* CONVERSATIONS
    draw_relationship_line(c, MARGIN + 100*mm, y - 30*mm, MARGIN + 100*mm, y - 50*mm, "1", "*", "gère")
    
    # CONVERSATIONS 1--* PARTICIPANTS
    draw_relationship_line(c, MARGIN + 55*mm, y - 30*mm, MARGIN + 130*mm, y - 30*mm, "1", "*", "inclut")
    
    # CONVERSATIONS 1--* MESSAGES
    draw_relationship_line(c, MARGIN + 55*mm, y - 50*mm, MARGIN + 195*mm, y - 50*mm, "1", "*", "contiennent")
    
    # COMMUNAUTES 1--* INVITATIONS
    draw_relationship_line(c, MARGIN + 120*mm, y - 30*mm, MARGIN + 260*mm, y - 30*mm, "1", "*", "envoie")
    
    # Pied de page
    c.setFillColor(GRIS_MOYEN)
    c.setFont("Helvetica", 8)
    c.drawString(MARGIN, 10*mm, "Cado.me - MCD - Page 4/4")
    c.drawRightString(PAGE_WIDTH - MARGIN, 10*mm, "Modèle Conceptuel de Données")


def draw_summary_page(c):
    """Page de synthèse avec le diagramme simplifié"""
    y = PAGE_HEIGHT - 15*mm
    
    # En-tête
    y = draw_header(c, y)
    y -= 10*mm
    
    # Titre
    c.setFillColor(VIOLET)
    c.setFont("Helvetica-Bold", 14)
    c.drawString(MARGIN, y, "SYNTHÈSE: DIAGRAMME ENTITÉS-RELATIONS")
    y -= 12*mm
    
    # Légende
    c.setFillColor(GRIS)
    c.rect(MARGIN, y - 40*mm, PAGE_WIDTH - 2*MARGIN, 45*mm, fill=1, stroke=0)
    
    c.setFillColor(VIOLET_FONCE)
    c.setFont("Helvetica-Bold", 10)
    c.drawString(MARGIN + 5*mm, y - 5*mm, "LÉGENDE")
    
    c.setFont("Helvetica", 9)
    c.setFillColor(GRIS_FONCE)
    c.drawString(MARGIN + 5*mm, y - 12*mm, "PK = Clé Primaire (Primary Key)")
    c.drawString(MARGIN + 5*mm, y - 18*mm, "FK = Clé Étrangère (Foreign Key)")
    c.drawString(MARGIN + 100*mm, y - 12*mm, "1 = Cardnalité un")
    c.drawString(MARGIN + 100*mm, y - 18*mm, "* = Cardnalité many (plusieurs)")
    c.drawString(MARGIN + 5*mm, y - 24*mm, "ENUM = Énumération (valeurs prédéfinies)")
    c.drawString(MARGIN + 100*mm, y - 24*mm, "UNIQUE = Contrainte d'unicité")
    c.drawString(MARGIN + 5*mm, y - 30*mm, "JSON = Données au format JSON")
    c.drawString(MARGIN + 100*mm, y - 30*mm, "TEXT/LONGTEXT = Texte long")
    
    y -= 50*mm
    
    # Tableau récapitulatif des entités
    c.setFillColor(VIOLET)
    c.setFont("Helvetica-Bold", 12)
    c.drawString(MARGIN, y, "RÉCAPITULATIF DES ENTITÉS")
    y -= 8*mm
    
    # En-tête du tableau
    c.setFillColor(VIOLET_FONCE)
    c.rect(MARGIN, y - 6*mm, PAGE_WIDTH - 2*MARGIN, 6*mm, fill=1, stroke=0)
    c.setFillColor(BLANC)
    c.setFont("Helvetica-Bold", 8)
    c.drawString(MARGIN + 5*mm, y - 4.5*mm, "ENTITÉ")
    c.drawString(MARGIN + 60*mm, y - 4.5*mm, "DESCRIPTION")
    c.drawString(MARGIN + 150*mm, y - 4.5*mm, "ATTRIBUTS CLÉS")
    c.drawString(MARGIN + 230*mm, y - 4.5*mm, "RELATIONS")
    y -= 6*mm
    
    # Données du tableau
    entities = [
        ("UTILISATEURS", "Comptes utilisateurs de la plateforme", "id, email, nom, prénom, rôles", "Crée des communautés, publie, commente"),
        ("COMMUNAUTÉS", "Espaces communautaires isolés", "id, slug, nom, propriétaire", "Contient publications, formations, membres"),
        ("MEMBRES COMMUNAUTÉS", "Appartenance aux communautés", "utilisateur, communauté, rôle", "Lie utilisateurs et communautés"),
        ("PUBLICATIONS", "Contenus publiés dans les feeds", "contenu, type, statut", "Appartient à une communauté, a des médias"),
        ("MÉDIAS PUBLICATIONS", "Fichiers joints aux publications", "type, chemin, taille", "Appartient à une publication"),
        ("COMMENTAIRES", "Réactions aux publications", "contenu, parent (auto-réf)", "Sur publication, par utilisateur"),
        ("LIKES PUBLICATIONS", "Appréciations", "utilisateur, publication", "Unique par utilisateur/publication"),
        ("FORMATIONS", "Parcours éducatifs", "titre, slug, statut", "Contient des leçons, dans une communauté"),
        ("LEÇONS", "Éléments de formation", "titre, contenu, vidéo", "Dans une formation, suivies par membres"),
        ("PROGRESSION FORMATIONS", "Suivi d'apprentissage", "utilisateur, leçon, terminée", "Lie membres et leçons"),
        ("RESSOURCES", "Documents et liens partagés", "titre, type, chemin", "Dans une communauté"),
        ("ÉVÉNEMENTS", "Événements communautaires", "titre, dates, type", "Dans une communauté"),
        ("NOTIFICATIONS", "Alertes utilisateurs", "type, titre, message", "Pour un utilisateur dans une communauté"),
        ("CONVERSATIONS", "Fils de discussion", "communauté", "Contient messages et participants"),
        ("MESSAGES", "Échanges privés", "contenu, lu", "Dans une conversation, par un utilisateur"),
        ("SIGNALEMENTS", "Modération", "motif, statut", "Sur publication/commentaire"),
        ("INVITATIONS", "Invitations à rejoindre", "email, token, rôle", "Pour une communauté"),
        ("PLANS", "Offres d'abonnement", "prix, limites", "Définit les abonnements"),
        ("ABONNEMENTS", "Souscriptions actives", "plan, statut, périodes", "Pour une communauté à un plan"),
        ("JOURNAUX AUDIT", "Historique des actions", "action, entité, IP", "Traçabilité globale"),
        ("PARAMÈTRES PLATEFORME", "Configuration globale", "nom, maintenance", "Singleton (une seule ligne)"),
    ]
    
    row_height = 5.5*mm
    for i, (name, desc, keys, rels) in enumerate(entities):
        if y < 15*mm:
            break
        
        # Couleur alternée
        if i % 2 == 0:
            c.setFillColor(GRIS)
            c.rect(MARGIN, y - row_height, PAGE_WIDTH - 2*MARGIN, row_height, fill=1, stroke=0)
        
        c.setFillColor(GRIS_FONCE)
        c.setFont("Helvetica-Bold", 7)
        c.drawString(MARGIN + 5*mm, y - 4*mm, name)
        
        c.setFont("Helvetica", 7)
        c.drawString(MARGIN + 60*mm, y - 4*mm, desc[:45] + "..." if len(desc) > 45 else desc)
        c.drawString(MARGIN + 150*mm, y - 4*mm, keys[:30] + "..." if len(keys) > 30 else keys)
        c.drawString(MARGIN + 230*mm, y - 4*mm, rels[:40] + "..." if len(rels) > 40 else rels)
        
        y -= row_height
    
    # Diagramme simplifié
    y -= 10*mm
    c.setFillColor(VIOLET)
    c.setFont("Helvetica-Bold", 12)
    c.drawString(MARGIN, y, "ARCHITECTURE MULTI-TENANT")
    y -= 8*mm
    
    c.setFillColor(VIOLET_TRES_CLAIR)
    c.setStrokeColor(VIOLET)
    c.setLineWidth(1)
    
    # Boîte Plateforme
    c.roundRect(MARGIN + 30*mm, y - 30*mm, 60*mm, 30*mm, 3*mm, fill=1, stroke=1)
    c.setFillColor(VIOLET_FONCE)
    c.setFont("Helvetica-Bold", 9)
    c.drawCentredString(MARGIN + 60*mm, y - 12*mm, "PLATEFORME")
    c.setFont("Helvetica", 7)
    c.drawCentredString(MARGIN + 60*mm, y - 18*mm, "Paramètres")
    c.drawCentredString(MARGIN + 60*mm, y - 23*mm, "Utilisateurs")
    c.drawCentredString(MARGIN + 60*mm, y - 28*mm, "Plans & Abonnements")
    
    # Boîte Communauté A
    c.roundRect(MARGIN + 110*mm, y - 30*mm, 60*mm, 30*mm, 3*mm, fill=1, stroke=1)
    c.setFillColor(VIOLET_FONCE)
    c.drawCentredString(MARGIN + 140*mm, y - 12*mm, "COMMUNAUTÉ A")
    c.setFont("Helvetica", 7)
    c.drawCentredString(MARGIN + 140*mm, y - 18*mm, "Publications, Formations")
    c.drawCentredString(MARGIN + 140*mm, y - 23*mm, "Membres, Événements")
    c.drawCentredString(MARGIN + 140*mm, y - 28*mm, "Messages, Ressources")
    
    # Boîte Communauté B
    c.roundRect(MARGIN + 190*mm, y - 30*mm, 60*mm, 30*mm, 3*mm, fill=1, stroke=1)
    c.setFillColor(VIOLET_FONCE)
    c.drawCentredString(MARGIN + 220*mm, y - 12*mm, "COMMUNAUTÉ B")
    c.setFont("Helvetica", 7)
    c.drawCentredString(MARGIN + 220*mm, y - 18*mm, "Même structure")
    c.drawCentredString(MARGIN + 220*mm, y - 23*mm, "Données isolées")
    c.drawCentredString(MARGIN + 220*mm, y - 28*mm, "par communaute_id")
    
    # Lignes de relation
    c.setStrokeColor(VIOLET_CLAIR)
    c.setLineWidth(1.5)
    c.line(MARGIN + 90*mm, y - 15*mm, MARGIN + 110*mm, y - 15*mm)
    c.line(MARGIN + 170*mm, y - 15*mm, MARGIN + 190*mm, y - 15*mm)
    
    # Flèches
    c.setFillColor(VIOLET_CLAIR)
    c.setFont("Helvetica", 8)
    c.drawCentredString(MARGIN + 100*mm, y - 13*mm, "→")
    c.drawCentredString(MARGIN + 180*mm, y - 13*mm, "→")
    
    # Note
    y -= 35*mm
    c.setFillColor(VIOLET_TRES_CLAIR)
    c.setStrokeColor(VIOLET)
    c.roundRect(MARGIN, y - 15*mm, PAGE_WIDTH - 2*MARGIN, 15*mm, 2*mm, fill=1, stroke=1)
    c.setFillColor(VIOLET_FONCE)
    c.setFont("Helvetica-Bold", 9)
    c.drawString(MARGIN + 5*mm, y - 5*mm, "RÈGLE FONDAMENTALE DU MULTI-TENANCY:")
    c.setFont("Helvetica", 8)
    c.drawString(MARGIN + 5*mm, y - 11*mm, "Chaque donnée métier contient un champ communaute_id pour garantir l'isolation entre communautés.")
    
    # Pied de page
    c.setFillColor(GRIS_MOYEN)
    c.setFont("Helvetica", 8)
    c.drawString(MARGIN, 10*mm, "Cado.me - MCD - Synthèse")
    c.drawRightString(PAGE_WIDTH - MARGIN, 10*mm, "Modèle Conceptuel de Données")


def main():
    filename = "MCD_Cado.me.pdf"
    
    c = canvas.Canvas(filename, pagesize=landscape(A4))
    c.setTitle("MCD - Cado.me")
    c.setAuthor("Cado.me")
    c.setSubject("Modèle Conceptuel de Données - Plateforme SaaS Multi-Communautés")
    
    # Page de synthèse
    draw_summary_page(c)
    c.showPage()
    
    # Page 1: Entités principales
    draw_page1(c)
    c.showPage()
    
    # Page 2: Publications et interactions
    draw_page2(c)
    c.showPage()
    
    # Page 3: Formations et ressources
    draw_page3(c)
    c.showPage()
    
    # Page 4: Notifications et système
    draw_page4(c)
    c.showPage()
    
    c.save()
    print(f"PDF généré avec succès: {filename}")
    print(f"5 pages au format A4 paysage")


if __name__ == "__main__":
    main()
