from PIL import Image
import os

def compress_image(input_path, output_path, quality=70, max_width=1200):
    """
    Compresse une image pour le web :
    - Réduction de la qualité
    - Redimensionnement si trop large
    """

    img = Image.open(input_path)

    # Convertir en RGB si nécessaire (important pour JPEG)
    if img.mode in ("RGBA", "P"):
        img = img.convert("RGB")

    # Redimensionnement (garde le ratio)
    if img.width > max_width:
        ratio = max_width / img.width
        new_height = int(img.height * ratio)
        img = img.resize((max_width, new_height), Image.LANCZOS)

    # Sauvegarde compressée
    img.save(output_path, "JPEG", quality=quality, optimize=True)

    print(f"✅ Compressée : {input_path} → {output_path}")


def compress_folder(input_folder, output_folder, quality=70, max_width=1200):
    if not os.path.exists(output_folder):
        os.makedirs(output_folder)

    for filename in os.listdir(input_folder):
        if filename.lower().endswith((".jpg", ".jpeg", ".png")):
            input_path = os.path.join(input_folder, filename)

            # Force extension en .jpg (plus léger)
            output_filename = os.path.splitext(filename)[0] + ".jpg"
            output_path = os.path.join(output_folder, output_filename)

            compress_image(input_path, output_path, quality, max_width)


# 🔥 UTILISATION
input_folder = "./"
output_folder = "./compressed"

compress_folder(input_folder, output_folder, quality=65, max_width=1200)