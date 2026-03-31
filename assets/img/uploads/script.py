from PIL import Image
import os

def compress_image(input_path, output_path, quality=70, max_width=960):
    """
    Compresse une image pour le web :
    - Réduction de la qualité
    - Redimensionnement si trop large
    """

    img = Image.open(input_path)

    # Redimensionnement (garde le ratio)
    if img.width > max_width:
        ratio = max_width / img.width
        new_height = int(img.height * ratio)
        img = img.resize((max_width, new_height), Image.LANCZOS)

    # Sauvegarde compressée selon l'extension
    ext = os.path.splitext(output_path)[1].lower()
    if ext in (".jpg", ".jpeg"):
        if img.mode in ("RGBA", "P"):
            img = img.convert("RGB")
        img.save(output_path, "JPEG", quality=quality, optimize=True)
    elif ext == ".png":
        img.save(output_path, "PNG", optimize=True, compress_level=6)
    elif ext == ".gif":
        img.save(output_path, "GIF")
    else:
        img.save(output_path, "JPEG", quality=quality, optimize=True)

    print(f"✅ Compressée : {input_path} → {output_path}")


def compress_folder(input_folder, output_folder, widths, quality=70):
    if not os.path.exists(output_folder):
        os.makedirs(output_folder)

    for filename in os.listdir(input_folder):
        if filename.lower().endswith((".jpg", ".jpeg", ".png")):
            input_path = os.path.join(input_folder, filename)

            base_name, ext = os.path.splitext(filename)
            ext = ext.lower()
            parts = base_name.rsplit("-", 1)
            if len(parts) == 2 and parts[1] in {"480", "960"}:
                continue
            for width in widths:
                output_filename = f"{base_name}-{width}{ext}"
                output_path = os.path.join(output_folder, output_filename)
                compress_image(input_path, output_path, quality, width)


# 🔥 UTILISATION
input_folder = "./"
output_folder = "./compressed_480_960"

compress_folder(input_folder, output_folder, widths=[480, 960], quality=65)