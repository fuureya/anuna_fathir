from flask import Flask, jsonify, request # type: ignore
import math

app = Flask(__name__)

def haversine(lat1, lon1, lat2, lon2):
    R = 6371  # Radius bumi dalam kilometer
    dlat = math.radians(lat2 - lat1)
    dlon = math.radians(lon2 - lon1)
    a = (math.sin(dlat / 2) ** 2 +
         math.cos(math.radians(lat1)) * math.cos(math.radians(lat2)) * math.sin(dlon / 2) ** 2)
    c = 2 * math.asin(math.sqrt(a))
    return R * c  # Jarak dalam kilometer

def greedy_tsp(locations):
    visited = []
    current_location = locations[0]  # Mulai dari lokasi pertama
    visited.append(current_location)
    total_distance = 0

    while len(visited) < len(locations):
        nearest_location = None
        nearest_distance = float('inf')

        for location in locations:
            if location not in visited:
                distance = haversine(current_location['latitude'], current_location['longitude'],
                                     location['latitude'], location['longitude'])
                if distance < nearest_distance:
                    nearest_distance = distance
                    nearest_location = location

        total_distance += nearest_distance
        visited.append(nearest_location)
        current_location = nearest_location

    # Kembali ke lokasi awal
    total_distance += haversine(current_location['latitude'], current_location['longitude'],
                                 locations[0]['latitude'], locations[0]['longitude'])

    return visited, total_distance

@app.route('/optimize_route', methods=['POST'])
def optimize_route():
    data = request.json  # Ambil data reservasi dari permintaan
    locations = data['reservations']  # Data lokasi reservasi
    optimal_route, total_distance = greedy_tsp(locations)
    
    return jsonify({
        'route': optimal_route,
        'total_distance': total_distance
    })

if __name__ == '__main__':
    app.run(debug=True)